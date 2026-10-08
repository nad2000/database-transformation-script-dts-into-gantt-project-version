<?php

/**
 *
 * @param SimpleXMLElement $el Mailstone, event, or todo-item element
 * @return int Task duration in days
 */
function TaskDuration($el) {
	if ( $el->getName()=='todo-item'){
		$start =  $el->{'created-on'};
		$end = $el->{'due-at'};
	} else {
		$start =  $el->{'start-at'};
		$end = $el->deadline;
	}
	$startTs = strtotime((string)$start);
	$endTs = strtotime((string)$end);
	$diff = $endTs - $startTs;
	return (int)round($diff / 86400.);
} 

/**
 * Account project XML document transfromer into
 * Gantt Project version 2.5 'Praha' XML format
 */
class GanttProject {

	/** @var SimpleXMLElement $el */
	private $el;

	/** @var SimpleXMLElement $current_project */
	private $current_project;

	/**
	 * Single element value or array or element values mathing xpath
	 *
	 * @param string $xpath
	 * @return sting or array() of sting or SimpleXMLElement
	 */
	public function xpVal($xpath, $el=NULL) {
		if (!isset ($el)) $el=$this->current_project;
		$res = $el->xpath( $xpath);
		if ( empty($res) ) return NULL;
		if ( count($res)==1) return (string)$res[0];
		if ($res[0]->count()==0) foreach( $res as $key=>$el) $res[$key]=(string)$el;
		return $res;
	}

	/**
	 *
	 * @param string $filename Basecamp Account XML file
	 * @param string $destDir Directry where to store Gantt Project files
	 * @return SimpleXMLElement
	 */
	public function transform($filename, $destDir = '.') {
		/**
		 * @var $account SimpleXMLElement account XML file transformed into object
		 */
		$this->account = $account = simplexml_load_file ( $filename);
		$this->destDirectory = realpath( $destDir);
		//print_r($account);
		return $this->transformIntoProjects( $account, $destDir);

	}

	/**
	 * @param SimpleXMLElement $account
	 */
	public function transformIntoProjects( $account) {
		/** @var SimpleXMLElement $el */
		$this->account = $this->el = $el = $account;
		$this->_errors = array();

		// Return Lines
		$returnLines = array();
		$projects = $account->xpath('//project');
		foreach( $projects as $proj) {
			$projectName = (string)$proj->name[0];
			$projectFilename = realpath( $this->destDirectory .'/'. $projectName.".xml");
			/** @var SimpleXMLElement $xml */
			$xml = $this->transformIntoProject($proj);
			$xml->saveXML( $projectFilename);
		}
		
	}
	
	/**
	 * @param SimpleXMLElement $proj
	 * @return array() of SimpleXMLElement
	 */
	public function transformIntoProject( $proj  ) {

		$account = $el = $this->account;
		// generating teh document:
		ob_start();
		echo "<?xml version=\"1.0\"?>\n";
		?>
<project
	name="<?=$proj->name[0]?>"
	company="<?=$el->firm->name[0]?>"
	webLink="<?=$el->firm->{'web-address'}?>"
	view-date="2012-01-16" view-index="0"
	gantt-divider-location="353"
	resource-divider-location="300"
	version="2.0"> 
    <description><![CDATA[Project generated form Basecamp account file "<?=$el->name?>"]]></description>
    <view zooming-state="default:1" id="gantt-chart"/>
    <view id="resource-table">
        <field id="1" name="Default role" width="29" order="0"/>
        <field id="0" name="Name" width="70" order="1"/>
    </view>
    <!-- -->
    <calendars>
        <day-types>
            <day-type id="0"/>
            <day-type id="1"/>
            <calendar id="1" name="default">
                <default-week sun="1" mon="0" tue="0" wed="0" thu="0" fri="0" sat="1"/>
                <only-show-weekends value="false"/>
                <overriden-day-types/>
                <days/>
            </calendar>
        </day-types>
    </calendars>
    <tasks color="#8cb6ce">
        <taskproperties>
            <taskproperty id="tpd0" name="type" type="default" valuetype="icon"/>
            <taskproperty id="tpd1" name="priority" type="default" valuetype="icon"/>
            <taskproperty id="tpd2" name="info" type="default" valuetype="icon"/>
            <taskproperty id="tpd3" name="name" type="default" valuetype="text"/>
            <taskproperty id="tpd4" name="begindate" type="default" valuetype="date"/>
            <taskproperty id="tpd5" name="enddate" type="default" valuetype="date"/>
            <taskproperty id="tpd6" name="duration" type="default" valuetype="int"/>
            <taskproperty id="tpd7" name="completion" type="default" valuetype="int"/>
            <taskproperty id="tpd8" name="coordinator" type="default" valuetype="text"/>
            <taskproperty id="tpd9" name="predecessorsr" type="default" valuetype="text"/>
            <taskproperty id="784873824" name="Custom" type="custom" valuetype="text" defaultvalue="Custom Value"/>
        </taskproperties><?php
		// Events:
		foreach ( $proj->{'calendar-events'}->event as $event ) echo <<<EOD
			<task id="{$event->id}" name="{$event->title}" color="#8cb6ce"
				meeting="false" start="{$event->{"start-at"}}" duration="1" complete="0" expand="true"/>
EOD;
		// Milestones:
		foreach ( $proj->milestones->milestone as $ms ) {
			$duration = TaskDuration($ms);
			echo <<<EOD
			<task 
				id="{$ms->id}" name="{$ms->title}" color="#660000"
				meeting="true" start="{$event->{"start-at"}}" 
				duration="{$duration}" complete="0" expand="true"/>
EOD;
		}
		foreach ( $proj->{'todo-lists'}->{'todo-list'} as $todoList ) {
			$msId = (int)$todoList->{'milestone-id'};
			echo <<<EOD
			<task 
				id="{$todoList->id}" name="{$todoList->name[0]}" color="#ccffcc"
				meeting="true" expand="true">
				<depend id="{$msId}" type="2" difference="0" hardness="Strong"/>
			</task>
EOD;
			foreach ($todoList->{'todo-items'}->{'todo-item'} as $todo ) {
				$completed = (string)$todo->completed == 'true' ? 1 : 0;
				$duration = TaskDuration($todo);
				echo <<<EOD
			<task 
				id="{$todo->id}" name="{$todo->content}" color="#8cb6ce"
				meeting="false" start="{$todo->{'created-on'}}" 
				duration="{$duration}" complete="{$completed}" expand="true">
				<depend id="{$todoList->id}" type="2" difference="0" hardness="Strong"/>
			</task>
EOD;
			}
		}
		?>
    </tasks>
    <resources><?php
		foreach ($el->firm->people->person as $person) echo <<<EOD
		<resource id="{$person->id}" 
			name="{$person->name}" 
			function="Default:0" 
			contacts="{$person->{'email-address'}}" 
			phone="{$person->{'phone-number-office'}}" />
EOD;
	?>
    </resources>
    <allocations><?php
		foreach ( $proj->{'calendar-events'}->event as $event ) echo <<<EOD
        <allocation 
			task-id="{$event->id}" resource-id="{$event->{'creator-id'}}" 
			function="Default:0" responsible="true" load="100.0"/>
EOD;
		foreach ( $proj->milestones->milestone as $ms ) echo <<<EOD
        <allocation 
			task-id="{$ms->id}" resource-id="{$ms->{'responsible-party-id'}}" 
			function="Default:0" responsible="true" load="100.0"/>
EOD;
			foreach ($todoList->{'todo-items'}->{'todo-item'} as $todo ) {
				$completed = (string)$todo->completed == 'true' ? 1 : 0;
				$duration = TaskDuration($todo);
				echo <<<EOD
        <allocation 
			task-id="{$todo->id}" resource-id="{$todo->{'responsible-party-id'}}" 
			function="Default:0" responsible="true" load="100.0"/>
EOD;
			}
	?>
    </allocations>
    <vacations/>
    <taskdisplaycolumns>
        <displaycolumn property-id="tpd7" order="-1" width="75" visible="false"/>
        <displaycolumn property-id="tpd8" order="-1" width="75" visible="false"/>
        <displaycolumn property-id="tpd6" order="-1" width="75" visible="false"/>
        <displaycolumn property-id="tpd10" order="-1" width="75" visible="false"/>
        <displaycolumn property-id="tpd2" order="-1" width="75" visible="false"/>
        <displaycolumn property-id="tpd9" order="-1" width="75" visible="false"/>
        <displaycolumn property-id="tpd1" order="-1" width="75" visible="false"/>
        <displaycolumn property-id="tpd0" order="-1" width="75" visible="false"/>
        <displaycolumn property-id="tpd3" order="0" width="152" visible="true"/>
        <displaycolumn property-id="tpd4" order="1" width="59" visible="true"/>
        <displaycolumn property-id="tpd5" order="2" width="60" visible="true"/>
        <displaycolumn property-id="784873824" order="3" width="78" visible="false"/>
    </taskdisplaycolumns>
    <previous/>
    <roles roleset-name="Default"/>
    <roles roleset-name="SoftwareDevelopment"/>
    <roles><?php
		foreach ($el->firm->people->person as $person) {
			echo <<<EOD
		<role id="{$person->id}" name="{$person->name}"/>
EOD;
		}
	?>
    </roles>
</project>
<?php
		$xmlStr=ob_get_contents();
		ob_end_clean();

		// print $xmlStr;

		$this->_raw = $xmlStr;
		$this->_xml = simplexml_load_string($xmlStr);

		$this->_errors = array_merge( $this->_errors, libxml_get_errors());
		libxml_clear_errors();
		return $this->_xml;

	}
}
