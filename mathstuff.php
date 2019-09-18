<?php
    $nCols = 50;
    $nRows = 10;
?>
<html>
    <head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel='stylesheet' href='css/fusionlib.css' type="text/css" media="screen" charset="utf-8">
        <link rel='stylesheet' href='css/mathstuff.css' type="text/css" media="screen" charset="utf-8">
        <script language="javascript" type="text/javascript" src="javascript/jquery-3.4.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script><script language="javascript" type="text/javascript" src="javascript/mathstuff.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
        <script language="javascript" type="text/javascript" src="javascript/mathstuff.js"></script>
        <style>
            ul {
                float:left;
                padding:0px;
                width:100%;
            }
            ul li {
                display: inline-block;
            }
        </style>
    </head>
    <body>
        <div class="container" style="padding-top:50px;">
            <div style="float:left;width: 100%;">
                <h4>
                    Array Calculations
                </h4>
                <div style="float:left;width:50%;text-align:center;">
                    <textarea style="padding:10px;float:left;margin-top: 10ox;margin-bottom: 10px;" rows="<?php echo $nRows; ?>" cols="<?php echo $nCols ?>"></textarea>
                </div>
                <div style="float:left;width:50%;text-align:center;">
                    <textarea style="padding:10px;float:left;margin-top: 10ox;margin-bottom: 10px;" rows="<?php echo $nRows; ?>" cols="<?php echo $nCols ?>"></textarea>
                </div>

                <ul>
                    <li>
                        <button class="btn btn-primary" type="button" onclick="multiplyMatrices()">
                            Multiply Matrices
                        </button>
                    </li>
                    <li>
                        <button class="btn btn-primary" type="button" onclick="calculateEigenVector()">
                            Calculate Eigenvector
                        </button>
                    </li>
                    <li>
                        <button class="btn btn-primary" type="button" onclick="addTableRow('testtable')">
                            Add Table Row
                        </button>
                    </li>
                    <li>
                        <button class="btn btn-primary" type="button" onclick="addTableColumn('testtable')">
                            Add Table Column
                        </button>
                    </li>

                </ul>
                <table id="testtable">
                    <thead>
                        <tr>
                            <th></th>
                            <th><button class="btn btn-danger btn-sm" onclick="removeTableColumn(this)">X</button></th>
                            <th><button class="btn btn-danger btn-sm" onclick="removeTableColumn(this)">X</button></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><button class="btn btn-danger btn-sm" onclick="removeTableRow(this)">X</button></td>
                            <td><input type="text" value="0" /></td>
                            <td><input type="text" value="0" /></td></tr>
                        <tr><td><button class="btn btn-danger btn-sm" onclick="removeTableRow(this)">X</button></td>
                            <td><input type="text" value="0" /></td>
                            <td><input type="text" value="0" /></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>