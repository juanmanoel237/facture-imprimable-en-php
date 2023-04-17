<?php require "config/consts.php"; ?>
<html>

<head>
    <title>Create Printable PDF invoice using PHP MySQL</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

    <link rel='stylesheet' href='https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css'>
    <script src="https://code.jquery.com/ui/1.13.0-rc.3/jquery-ui.min.js"
        integrity="sha256-R6eRO29lbCyPGfninb/kjIXeRjMOqY3VWPVk6gMhREk=" crossorigin="anonymous"></script>

</head>

<body>
    <div class='container pt-5'>
        <h1 class='text-center text-primary'>Créer une facture PDF imprimable à l'aide de PHP MySQL</h1>
        <hr>
        <?php
        if (isset($_POST["submit"])) {
            $num_facture = $_POST["num_facture"];
            $date_facture = date("Y-m-d", strtotime($_POST["date_facture"]));
            $cnom = mysqli_real_escape_string($connex, $_POST["cnom"]);
            $cadresse = mysqli_real_escape_string($connex, $_POST["cadresse"]);
            $cville = mysqli_real_escape_string($connex, $_POST["cville"]);
            $grand_total = mysqli_real_escape_string($connex, $_POST["grand_total"]);

            $sql = "INSERT INTO facture (NUM_FACTURE,DATE_FACTURE,CNOM,CADRESSE,CVILLE,GRAND_TOTAL) values ('{$num_facture}','{$date_facture}','{$cnom}','{$cadresse}','{$cville}','{$grand_total}') ";
            if ($connex->query($sql)) {
                $sid = $connex->insert_id;

                $sql2 = "INSERT INTO facture_produits (SID,PNOM,PRIX,QTY,TOTAL) values ";
                $rows = [];
                for ($i = 0; $i < count($_POST["pnom"]); $i++) {
                    $pnom = mysqli_real_escape_string($connex, $_POST["pnom"][$i]);
                    $prix = mysqli_real_escape_string($connex, $_POST["prix"][$i]);
                    $qty = mysqli_real_escape_string($connex, $_POST["qty"][$i]);
                    $total = mysqli_real_escape_string($connex, $_POST["total"][$i]);
                    $rows[] = "('{$sid}','{$pnom}','{$prix}','{$qty}','{$total}')";
                }
                $sql2 .= implode(",", $rows);
                if ($connex->query($sql2)) {
                    echo "<div class='alert alert-success'>Facture Ajoutée avec succès. <a href='print.php?id={$sid}' target='_BLANK'>Click </a> here to Print Invoice </div> ";
                } else {
                    echo "<div class='alert alert-danger'>Echec.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Facture Ajoutée avec succès.</div>";
            }
        }

        ?>
        <form method='post' action='index.php' autocomplete='off'>
            <div class='row'>
                <div class='col-md-4'>
                    <h5 class='text-success'>Détails facture</h5>
                    <div class='form-group'>
                        <label>No Facture</label>
                        <input type='text' name='num_facture' required class='form-control'>
                    </div>
                    <div class='form-group'>
                        <label>Date Facture</label>
                        <input type='text' name='date_facture' id='date' required class='form-control'>
                    </div>
                </div>
                <div class='col-md-8'>
                    <h5 class='text-success'>Détails consommateur</h5>
                    <div class='form-group'>
                        <label>Nom</label>
                        <input type='text' name='cnom' required class='form-control'>
                    </div>
                    <div class='form-group'>
                        <label>Adresse</label>
                        <input type='text' name='cadresse' required class='form-control'>
                    </div>
                    <div class='form-group'>
                        <label>Ville</label>
                        <input type='text' name='cville' required class='form-control'>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-12'>
                    <h5 class='text-success'>Détails Produit</h5>
                    <table class='table table-bordered'>
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Prix</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id='product_tbody'>
                            <tr>
                                <td><input type='text' required name='pnom[]' class='form-control'></td>
                                <td><input type='text' required name='prix[]' class='form-control price'></td>
                                <td><input type='text' required name='qty[]' class='form-control qty'></td>
                                <td><input type='text' required name='total[]' class='form-control total'></td>
                                <td><input type='button' value='x' class='btn btn-danger btn-sm btn-row-remove'> </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><input type='button' value='+ Add Row' class='btn btn-primary btn-sm'
                                        id='btn-add-row'></td>
                                <td colspan='2' class='text-right'>Total</td>
                                <td><input type='text' name='grand_total' id='grand_total' class='form-control'
                                        required></td>
                            </tr>
                        </tfoot>
                    </table>
                    <input type='submit' name='submit' value='Save Facture' class='btn btn-success float-right'>
                </div>
            </div>
        </form>
    </div>
    <script>
    $(document).ready(function() {
        $("#date").datepicker({
            dateFormat: "dd-mm-yy"
        });

        $("#btn-add-row").click(function() {
            var row =
                "<tr> <td><input type='text' required name='pname[]' class='form-control'></td> <td><input type='text' required name='price[]' class='form-control price'></td> <td><input type='text' required name='qty[]' class='form-control qty'></td> <td><input type='text' required name='total[]' class='form-control total'></td> <td><input type='button' value='x' class='btn btn-danger btn-sm btn-row-remove'> </td> </tr>";
            $("#product_tbody").append(row);
        });

        $("body").on("click", ".btn-row-remove", function() {
            if (confirm("Are You Sure?")) {
                $(this).closest("tr").remove();
                grand_total();
            }
        });

        $("body").on("keyup", ".price", function() {
            var price = Number($(this).val());
            var qty = Number($(this).closest("tr").find(".qty").val());
            $(this).closest("tr").find(".total").val(price * qty);
            grand_total();
        });

        $("body").on("keyup", ".qty", function() {
            var qty = Number($(this).val());
            var price = Number($(this).closest("tr").find(".price").val());
            $(this).closest("tr").find(".total").val(price * qty);
            grand_total();
        });

        function grand_total() {
            var tot = 0;
            $(".total").each(function() {
                tot += Number($(this).val());
            });
            $("#grand_total").val(tot);
        }
    });
    </script>
</body>

</html>