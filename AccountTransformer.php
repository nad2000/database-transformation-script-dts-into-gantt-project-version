<?php
/**
 * Account project XML document transfromer into
 * Gantt Project version 2.5 'Praha' XML format
 *
 * @author Radomirs Cirskis (nad2000@gmail.com)
 */
class AccountTransformer {
	function process($filename) {
		/**
		 * @var SimpleXMLElement account XML file transformed into object
		 */
		$account = $this->account = simplexml_load_file ( $filename);
		echo $account->id;

	}

}