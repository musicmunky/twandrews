<?php

	require '../php/shutdown.php';

	function adminer_object() {

		// required to run any plugin
		include_once "plugins/plugin.php";
		// autoloader
		foreach (glob("plugins/*.php") as $filename) {
			include_once $filename;
		}
		$plugins = array(
			// specify enabled plugins here
			new AdminerDatabaseHide(array('information_schema', 'mysql', 'performance_schema')),
			//new AdminerDumpJson,
			//new AdminerDumpBz2,
			//new AdminerDumpZip,
			//new AdminerDumpXml,
			//new AdminerDumpAlter,
			//~ new AdminerSqlLog("past-" . rtrim(`git describe --tags --abbrev=0`) . ".sql"),
			//new AdminerFileUpload(""),
			//new AdminerJsonColumn,
			//new AdminerSlugify,
			//new AdminerTranslation,
			//new AdminerForeignSystem,
			//new AdminerEnumOption,
			//new AdminerTablesFilter,
			//new AdminerEditForeign,
		);

		return new AdminerPlugin($plugins);
	}

	include "adminer.php";

?>

