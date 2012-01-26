<?php
/**
 * Account project XML document transfromer into
 * Gantt Project version 2.5 'Praha' XML format
 *
 * @author Radomirs Cirskis (nad2000@gmail.com)
 */
class AccountTransformer {

	static private	$__depluralizeRules = array(
				'ss' => false,
				'os' => 'o',
				'ies' => 'y',
				'xes' => 'x',
				'oes' => 'o',
				'ies' => 'y',
				'ves' => 'f',
				's' => '');

	static private	$__reservedMySQLWords = array( 'ssl' );


	function process($filename) {
		/**
		 * @var $account SimpleXMLElement account XML file transformed into object
		 */
		$account = $this->account = simplexml_load_file ( $filename);
		//print_r($account);
		$this->importElement( $account);

	}

	/**
	 * Load recursively XML element into DB
	 * @param SimpleXMLElement $el
	 */
	function importElement( $el) {

		if (  !( $el instanceof SimpleXMLElement) )	return;
		$elName = $el->getName();
		$tableName = $this->depluralize( $this->dbName( $el->getName()));
		$arr = (array)$el;
		// elements w/o final subelements
		if ( $elName=='default-attachment-categories' || $elName=='default-post-categories') {
			$sql = "INSERT IGNORE INTO $tableName (account_id,category) VALUES (?,?);";
			$stmt = DB::prepare( $sql);
			foreach( $arr['category'] as $cat ) {
				$stmt->execute( array($this->account_id,$cat) );
			}
			return;
		}
		if ( $elName=='participants') {
			$sql = "INSERT IGNORE INTO $tableName (project_id,person_id) VALUES (?,?);";
			$stmt = DB::prepare( $sql);
			/* @var $person_id int */
			foreach( $arr['person'] as $person_id ) {
				$stmt->execute( array($this->project_id,$person_id) );
			}
			return;
		}
		if ( (string)$el->attributes()->type == 'array' )  {
			foreach ($el->children() as $subEl) {
				$this->importElement( $subEl);
			}
			return;
		}
		$finals = $this->getFinals($el);
		if ( count($finals)==0 ) return;
		switch ($tableName) {
			case 'account':
				$this->account_id = $arr['id'];
				break;
			case 'project':
				$this->project_id = $arr['id'];
				break;
			case 'firm':
				$this->firm_id = $arr['id'];
				break;
//			case 'milestone':
//				$this->milestone_id = $arr['id'];
//				break;
		}
		if ( in_array( $elName , array( 'firm','subscription','project')))  {
			$finals['account_id'] = $this->account_id;
		} elseif ( $elName=='person') {
			$finals['firm_id'] = $this->firm_id;
		}
		if ( $elName=='project') {
			$finals['firm_id'] = (int)$el->company->id;
		}
		$sql = "INSERT INTO $tableName SET ";
		if (array_key_exists('id', $arr) ) $sql .="id=:id,";
		$valList = array();
		$map = array();
		foreach ($finals as $key => $value) {
			$col = $this->dbName($key);
			$valList[':'.$col]=$value;
			if ($col!='id') $map[]= $this->escapeDbName($col)."=:$col";
		}
		$mapStr = implode(',', $map);
		$sql .= $mapStr . ' ON DUPLICATE KEY UPDATE ' . $mapStr . ';';
		DB::execute( $sql, $valList);
		foreach( $arr as $subEl ) {
			if ( !is_string($subEl)) {
				if ( $subEl instanceof SimpleXMLElement
					 && in_array($subEl->getName(), array('company') ) )
					continue;
				$this->importElement( $subEl);
			}
		}
	}

	/**
	 * subelemnts name => value dictionary that don't have subelements
	 * excluding deprecated elements (deprecated="true")
	 *
	 * @param SimpleXMLElement $el
	 * @return array
	 */
	function getFinals($el) {
		$arr = (array)$el;
		$finals = array();
		foreach ($arr as $key=>$value) {

			if ( is_string($value) ) {
				if (isset($el->$key->attributes()->deprecated)) continue;
				$type = (string)$el->$key->attributes()->type;
				if ( (string)$el->$key->attributes()->nil=="true" ) {
					$finals[$key] = NULL;
				} elseif ( $type=='integer' ) {
					$finals[$key] = (int)$value;
				} elseif ( $type=='boolean') {
					$finals[$key] = ( $value=='true' ? 1 : 0);
				} elseif ( $type=='datetime') {
					$finals[$key] = substr( $value,0,-1);
				} else
					$finals[$key] = $value;
			}
			// Ommit duplicate 'name' elements
			elseif (  is_array( $value) && $key=='name')
				$finals[$key] = (string)$value[0];
		}
		return $finals;
	}

	/**
	 * Test if element type is "Array" (@type="array")
	 *
	 * @param SimpleXMLElement $el
	 * @return boolean
	 */
	function elIsArray($el) {
		return (string)$el->type == 'array';
	}

	/**
	 * Convert a work from plural to singular form
	 *
	 * @param string $word
	 * @return string
	 */
	function depluralize($word){
		// Loop through all the rules and do the replacement.
		foreach (self::$__depluralizeRules as $key => $value) {
			// If the end of the word doesn't match the key,
			// it's not a candidate for replacement. Move on
			// to the next plural ending.
			if(substr($word, (strlen($key) * -1)) != $key)
				continue;
			// If the value of the key is false, stop looping
			// and return the original version of the word.
			if($key === false)
				return $word;
			// We've made it this far, so we can do the
			// replacement.
			return substr($word, 0, strlen($word) - strlen($key)) . $value;
		}
		return $word;
	}

	/**
	 * Converts element name into DB table name or column name
	 *
	 * @param string $elementName
	 * @return string
	 */
	function dbName($elementName) {
		$dbName = strtolower( str_replace("-", "_", $elementName));
		return $dbName;
	}

	function escapeDbName($name) {
		if ( in_array($name, self::$__reservedMySQLWords)) {
			$name = '`'.$name.'`';
		}
		return $name;
	}

}