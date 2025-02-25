<!doctype html>
<html lang="en">

<head>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
</head>

<body>


  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script type="text/javascript" src="app.js"></script>
  <script>
    $(document).ready(function() {
        $('#selectAll').click(function() {
            if ($(this).is(':checked')) {
                $('.checkbox').prop('checked', true);
            } else {
                $('.checkbox').prop('checked', false);
            }
        });
    });
  </script>
  <script>
      $(document).ready(function(){
        $("#search-input").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#table-body tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
  </script>
  <script>
    $(document).ready(function() {
        $('#request-btn').click(function(e) {
            e.preventDefault();
            var selectedAssets = [];
            var user_id = '<?php echo $_SESSION["employee_id"]; ?>';
            var checkboxes = $('.checkbox:checked');
            if (checkboxes.length === 0) {
                alert('Please select at least one asset to request.');
                return;
            }
            var isValid = true;
            $('.checkbox:checked').each(function() {
                var type = $(this).val();
                var id = $('#id-' + type).val();
                var amount = $('#amount-' + type).val();
                var date_expected = $('#date-expected-' + type).val();
                var start_date = $('#start-date-' + type).val();
                var end_date = $('#end-date-' + type).val();
                if (start_date === '' || end_date === '') {
                    alert('Please select start and end date for ' + type);
                    isValid = false;
                    return false;
                }
                selectedAssets.push({ id: id, type: type, amount: amount, date_expected: date_expected, start_date: start_date, end_date: end_date });
            });
            if (!isValid) {
                return;
            }
            var queryString = '';
            var currentPath = window.location.pathname;
            if (currentPath.includes('assetres.php')) {
                queryString = '?assets=' + encodeURIComponent(JSON.stringify(selectedAssets)) + '&user_id=' + user_id + '&res=true';
            } else if (currentPath.includes('assetreq.php')) {
                queryString = '?assets=' + encodeURIComponent(JSON.stringify(selectedAssets)) + '&user_id=' + user_id + '&req=true';
            } else if (currentPath.includes('supplyreq.php')) {
                queryString = '?assets=' + encodeURIComponent(JSON.stringify(selectedAssets)) + '&user_id=' + user_id;
            } else {
                queryString = '?assets=' + encodeURIComponent(JSON.stringify(selectedAssets)) + '&user_id=' + user_id; // default path
            }
            var confirmPath = '';
            if (currentPath.includes('assetres.php')) {
                confirmPath = 'confirm_assetres.php';
            } else if (currentPath.includes('assetreq.php')) {
                confirmPath = 'confirm_assetreq.php';
            } else if (currentPath.includes('supplyreq.php')) {
                confirmPath = 'confirm_supplyreq.php';
            } else {
                confirmPath = 'confirm_assetreq.php'; // default path
            }
            window.location.href = confirmPath + queryString;
        });
    });
  </script>
  
</body>

</html>