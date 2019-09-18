
function multiplyMatrices() {
    alert("multiply");
}


function calculateEigenVector() {
    alert("calculate");
}


function addTableRow( sTableId )
{
    try
    {
        if( !checkTable( sTableId ) )
        {
            return false;
        }

        var oTable   = FUSION.get.node( sTableId ),
            oTbody   = oTable.tBodies[0],
            nNumCols = oTbody.rows[0].cells.length,
            oTrow    = document.createElement("tr"),
            oTcell   = null,
            oElement = null;

        for(var i = 0; i < nNumCols; i++)
        {
            oTcell = document.createElement("td");
            if( i == 0 ) {
                oElement = createTDButton( 'row' );
            }
            else {
                oElement = createTDInput();
            }
            oTcell.append( oElement );
            oTrow.append( oTcell );
        }

        oTbody.append(oTrow);
        return true;
    }
    catch(err){
        FUSION.error.logError(err);
        return false;
    }
}


function addTableColumn( sTableId )
{
    try
    {
        if( !checkTable( sTableId ) )
        {
            return false;
        }

        var oTable    = FUSION.get.node( sTableId ),
            oTbody    = oTable.tBodies[0],
            oTHeadRow = oTable.tHead.rows[0],
            nNumRows  = oTbody.rows.length,
            oTcell    = null,
            oElement  = null;

        var oTHeadCell = document.createElement("th");
        var oButton    = createTDButton( 'col' );
        oTHeadCell.append( oButton );
        oTHeadRow.append( oTHeadCell );
        for(var i = 0; i < nNumRows; i++)
        {
            oTcell   = document.createElement("td");
            oElement = createTDInput();
            oTcell.append( oElement );
            oTbody.rows[i].append( oTcell );
        }
        return true;
    }
    catch(err){
        FUSION.error.logError(err);
        return false;
    }
}


function createTDInput()
{
    var oInput = document.createElement("input");
    oInput.setAttribute( 'type', 'text' );
    oInput.setAttribute( 'value', '0' );
    return oInput;
}


function createTDButton( sRowOrColumn )
{
    var oButton = document.createElement("button");
    oButton.innerHTML = "X";
    oButton.setAttribute( 'class', 'btn btn-danger btn-sm');

    var sFunc = "removeTableRow(this)";
    if( sRowOrColumn == "col" ) {
        sFunc = "removeTableColumn(this)";
    }

    oButton.setAttribute( 'onclick', sFunc );

    return oButton;
}


function removeTableRow( oButton )
{
    var oTableRow = oButton.parentNode.parentNode;
    oTableRow.parentNode.removeChild( oTableRow );
}


function removeTableColumn( oButton )
{
    var nColNum   = oButton.parentNode.cellIndex,
        oTBody    = oButton.parentNode.parentNode.parentNode.parentNode.tBodies[0],
        oCell     = null;

    for(var i = 0; i < oTBody.rows.length; i++)
    {
        oCell = oTBody.rows[i].cells[nColNum];
        oCell.parentNode.removeChild( oCell );
    }

    oCell = oButton.parentNode;
    oCell.parentNode.removeChild( oCell );
}


function checkTable( sTableId )
{
    try
    {
        if( FUSION.lib.isBlank( sTableId ) )
        {
            console.error("Can not call addTableRow with blank table ID!");
            return false;
        }

        var oTable = FUSION.get.node( sTableId );
        if( typeof oTable == "undefined" || oTable == null )
        {
            console.error("Table '" + sTableId + "' not found!");
            return false;
        }

        var oTbody = oTable.tBodies[0];
        if( typeof oTbody == "undefined" || oTbody == null )
        {
            console.error("Table body for table '" + sTableId + "' not found!");
            return false;
        }

        return true;
    }
    catch(err){
        FUSION.error.logError(err);
        return false;
    }
}

