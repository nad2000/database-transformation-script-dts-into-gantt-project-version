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
		 * @var SimpleXMLElement account XML file transformed into object
		 */
		$account = $this->account = simplexml_load_file ( $filename);
		echo $account->id;
		$final_elements = array_filter((array)$account, "is_string");
		echo $final_elements->id;
		//print_r($final_elements);
		$this->importElement( $account);

	}
	
	/**
	 * Load recursively XML element into DB
	 * @param SimpleXMLElement $el 
	 */
	function importElement( $el) {

		if ( is_array($el) )  {
			foreach ($el as $subEl) {
				$this->importElement( $subEl);
			}
		}
		if (  !( $el instanceof SimpleXMLElement) )	return;
		$elName = $el->getName();
		$tableName = $this->depluralize( $this->dbName( $el->getName()));
		// elements w/o subelements
		$arr = (array)$el;
		if (array_key_exists('category', $arr) ) {
			$sql = "INSERT IGNORE INTO $tableName (account_id,category) VALUES (?,?);";
			$stmt = DB::prepare( $sql);
			//DB::execute( $sql, $valList);
			foreach( $arr['category'] as $cat ) {
				$stmt->execute( array($this->account_id,$cat) );
				//print_r( $stmt->errorInfo());
			}
			return;
		}
		$finals = array_filter($arr, "is_string");
		if ( count($finals)==0 ) return;
		if ( $elName=='account') {
			$this->account_id = $arr['id'];
		} 
		if ( $elName=='firm' || $elName=='subscription') {
			$finals['account_id'] = $this->account_id;
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
		echo $sql;
		foreach( $arr as $subEl ) {
			if ( !is_string($subEl) ) {
				$this->importElement( $subEl);
			}
		}
		
	}
	

	/**
	 * Convert a work drom plural to singular form
	 * 
	 * @param string $word
	 * @return string 
	 */
	function depluralize($word){
		// Loop through all the rules and do the replacement. 
		foreach (static::$__depluralizeRules as $key => $value) {
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
		if ( in_array($name, static::$__reservedMySQLWords)) {
			$name = '`'.$name.'`';
		}
		return $name;
	}

}