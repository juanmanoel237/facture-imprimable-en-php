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
    <div class="container pt-5">
        <form action="index.php" method="post" autocomplete="off">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="text-succes">Détails Facture</h5>
                    <div class="form-group">
                        <label>Num facture</label>
                        <input type="text" name="num_facture" require class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Date facture</label>
                        <input type="text" name="date_facture" id="date" require class="form-control">
                    </div>
                </div>
                <div class="col-md-8">
                    <h5 class="text-succes">Détails Consommateur</h5>
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="cnom" require class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Adresse</label>
                        <input type="text" name="cadresse" require class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Ville</label>
                        <input type="text" name="cville" require class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h5 class="text-success">Détails du produit</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Prix</th>
                                <th>Qté</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="product_tbody">
                            <tr>
                                <td><input type="text" require name="pname[]" class="form-control"></td>
                                <td><input type="text" require name="prix[]" class="form-control price"></td>
                                <td><input type="text" require name="qty[]" class="form-control qty"></td>
                                <td><input type="text" require name="total[]" class="form-control total"></td>
                                <td><input type="button" value="x" class="btn btn-danger btn-sm btn-row-remove"></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><input type="button" value="+ Add Row" class="btn btn-primary btn-sm"
                                        id="btn-add-row"></td>
                                <td colspan="2">Total</td>
                                <td><input type="text" name="grand_total" class="form-control" required></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
    </div>
    </form>
    <script>
    $(document).ready(function() {
        $("#date").datepicker({
            dateFormat: "dd-mm-yy"
        });

        $("#btn-add-row").click(function() {
            var row =
                `<tr> <td><input type="text" require name="pname[]" class="form-control"></td>
                    <td><input type="text" require name="price[]" class="form-control price"></td>
                    <td><input type="text" require name="qty[]" class="form-control qty"></td>
                    <td><input type="text" require name="total[]" class="form-control total"></td>
                    <td><input type="button" value="x" class="btn btn-danger btn-sm btn-row-remove"></td></tr>`
            $("#product_tbody").append(row)
        })
        $("body").on("click", ".btn-row-remove", function() {
            if (confirm("Etes-vous sûr(e)?")) {
                $(this).closest("tr").remove()
            }
        })
        $("body").on("keyup", ".price", function() {
            var price = Number($(this).val());
            var qty = Number($(this).closest("tr").find(".qty").val())
            $(this).closest("tr").find(".total").val(price * qty)
        })
        $("body").on("keyup", ".qty", function() {
            var qty = Number($(this).val());
            var price = Number($(this).closest("tr").find(".price").val())
            $(this).closest("tr").find(".total").val(price * qty)
        })


        function grand_total() {

        }
    })
    </script>

</body>

</html>