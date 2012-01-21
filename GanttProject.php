<?php

/**
 * Account project XML document transfromer into
 * Gantt Project version 2.5 'Praha' XML format
 *
 * @author Radomirs Cirskis (nad2000@gmail.com)
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
	 * @param string $filename
	 * @return SimpleXMLElement
	 */
	public function transform($filename) {
		/**
		 * @var $account SimpleXMLElement account XML file transformed into object
		 */
		$account = $this->account = simplexml_load_file ( $filename);
		//print_r($account);
		return $this->transformIntoProjects( $account);

	}

	/**
	 * @param SimpleXMLElement $account
	 * @return array() of SimpleXMLElement
	 */
	public function transformIntoProjects(
		$account ) {

		/** @var SimpleXMLElement $el */
		$this->el = $el = $account;

		$this->_errors = array();

		// Return Lines
		$returnLines = array();
		$projects = $account->xpath('//project');
		/** @var SimpleXMLElement $proj */
		$proj = $projects[0];
		// generating teh document:
		ob_start();
		echo "<?xml version=\"1.0\"?>\n";
		?>
<project
	name="<?=$proj->name[0]?>"
	company="<?=$el->firm->name[0]?>"
	webLink="<?=$el->firm->{'web-address'}?>"
	view-date="2012-01-16" view-index="1"
	gantt-divider-location="353"
	resource-divider-location="300"
	version="2.0">
    <description><![CDATA[Project generated form Basecamp account file "<?=$el->name?>"]]></description>
    <view zooming-state="default:0" id="gantt-chart"/>
    <view id="resource-table">
        <field id="0" name="Name" width="70" order="1"/>
        <field id="1" name="Default role" width="29" order="0"/>
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
		foreach ( $proj->{'calendar-events'} as $event ) {
			echo <<<EOD
			<task id="{$event->id}" name="{$event->title}" color="#8cb6ce"
				meeting="false" start="{$event->{"start-at"}}" duration="1" complete="0" expand="true"/>
EOD;
		}
		?>
        <task id="888" name="Stage 1 Milestone" color="#8cb6ce" meeting="true" start="2012-01-16" duration="1" complete="0" expand="true">
            <depend id="1" type="2" difference="0" hardness="Strong"/>
        </task>
        <task id="1" name="_1" color="#8cb6ce" meeting="false" start="2012-01-17" duration="1" complete="0" expand="true"/>
        <task id="2" name="_2" color="#8cb6ce" meeting="false" start="2012-01-16" duration="1" complete="0" expand="true">
            <depend id="1" type="2" difference="0" hardness="Strong"/>
        </task>
    </tasks>
    <resources>
        <resource id="0" name="Jane Doe" function="Default:0" contacts="me@myproject.com" phone=""/>
        <resource id="1" name="John Adams" function="Default:0" contacts="me2@myproject.com" phone=""/>
    </resources>
    <allocations>
        <allocation task-id="888" resource-id="0" function="Default:0" responsible="true" load="100.0"/>
        <allocation task-id="1" resource-id="1" function="Default:0" responsible="true" load="80.0"/>
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
        <displaycolumn property-id="784873824" order="3" width="78" visible="true"/>
    </taskdisplaycolumns>
    <previous>
        <previous-tasks name="xxxx">
            <previous-task id="888" start="2012-01-16" duration="1" meeting="true" super="false"/>
            <previous-task id="2" start="2012-01-16" duration="1" meeting="false" super="false"/>
            <previous-task id="1" start="2012-01-17" duration="1" meeting="false" super="false"/>
        </previous-tasks>
    </previous>
    <roles roleset-name="Default"/>
    <roles roleset-name="SoftwareDevelopment"/>
    <roles> <?php
		foreach ($el->firm->people->person as $person) {
			echo '
		<role id="'.(string)$person->id.'" name="'.(string)$person->name.'"/>';
		}
	?>
    </roles>
</project>
<?php
		$xmlStr=ob_get_contents();
		ob_end_clean();

		print $xmlStr;

		$this->_raw = $xmlStr;
		$this->_xml = simplexml_load_string($xmlStr);

		$this->_errors = array_merge( $this->_errors, libxml_get_errors());
		libxml_clear_errors();
		return $this->_xml;

	}
}

/**
 *
 * @param type $el
 * @param type $xpath
 */
function elVal($el, $xpath) {

}