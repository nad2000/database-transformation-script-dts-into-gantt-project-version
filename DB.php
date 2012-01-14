<?php
/**
 * Data access component
 *
 * @author Rad Cirskis (nad2000@gmail.com)
 */

class DB {

	static private $_user = 'root';
	static private $_pass = NULL;
	static private $_connectionString = 'mysql:host=localhost;dbname=securities';
	/*
	static private $_user = 'root' : 'kairos');
	static private $_pass = NULL : 'rC_1110_d3v');
	static private $_connectionString = 'mysql:host=hatfieldcompanies.com;dbname=securities';
	 */
	static private $_dbh = NULL;
	/**
	 *
	 * @return handler DB connection lazy loading
	 */
	static function cn() {
		if ( empty ( static::$_dbh) ) {
			static::$_dbh = new PDO(
						static::$_connectionString,
						static::$_user,
						static::$_pass );
		}
		return static::$_dbh;
	}

	/**
	 *
	 * @param integer $days days
	 * @return PDO statement for Log data retrieving
	 */
	static function getLogStmt( $days=3 ) {

		try {
			$dbh = static::cn();
			$stmt = $dbh->prepare(
			"SELECT
				LogId,
				ComputerName,
				DataSource,
				AmountOfRowsRead,
				AmountOfCompaniesRead,
				DataCollectionTimeSeconds,
				HistoryDataCollectionTimeSeconds,
				DataCalculationTimeSeconds,
				DataSavingTimeSeconds,
				TotalTime,
				StocksWithErrorsCount,
				CreationTime
			FROM log
			WHERE DATEDIFF( NOW(), CreationTime) <= ?
			ORDER BY CreationTime ASC");
			$stmt->execute( array( floatval($days )) );
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "\n\n";
			die();
		}

		return $stmt;
	}

	/**
	 *
	 * @param integer $lastLogId
	 * @return PDO statement for un-syncronized Log entries
	 */
	static function getUnsyncedLogStmt( $lastLogId) {

		try {
			$dbh = static::cn();
			$stmt = $dbh->prepare(
			"SELECT
				LogId,
				ComputerName,
				DataSource,
				AmountOfRowsRead,
				AmountOfCompaniesRead,
				DataCollectionTimeSeconds,
				HistoryDataCollectionTimeSeconds,
				DataCalculationTimeSeconds,
				DataSavingTimeSeconds,
				TotalTime,
				StocksWithErrorsCount,
				CreationTime
			FROM log
			WHERE LogId > ?
			ORDER BY CreationTime ASC");
			$stmt->execute( array( $lastLogId ) );
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "\n\n";
			die();
		}

		return $stmt;
	}

	/**
	 *
	 * @return integer Last processed LogId
	 */
	static function getLastLogId() {
		try {
			$dbh = static::cn();
			// ErrorLogs column or StocksWithErrors     column or Local Date Time
			$stmt = $dbh->query(
					"SELECT MAX(LastLogId) FROM log_export_trace ORDER BY LastLogId DESC LIMIT 1");
			$row = $stmt->fetch();
			$lastLogId = $row ? $row[0] : NULL;
			return $lastLogId;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "\n\n";
			die();
		}

	}

	/**
	 * Trace last exported LogId
	 * @param integer $lastLogId Last log ID exported
	 */
	static function storeLastLogId( $lastLogId) {
		try {
			$dbh = static::cn();
			// ErrorLogs column or StocksWithErrors     column or Local Date Time
			$stmt = $dbh->prepare( "INSERT IGNORE INTO log_export_trace (LastLogId) VALUES (?)");
			$stmt->execute( array($lastLogId));
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "\n\n";
			die();
		}
	}

}