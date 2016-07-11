<?php
require_once('phpgrid/conf.php');

class CI_phpgrid {

    public function grid_faktur()
    {
        $program = new C_DataGrid("SELECT * FROM tblExport_Faktur_Header",'ObjectID', "tblExport_Faktur_Header");
        return $program;
    }
	
}