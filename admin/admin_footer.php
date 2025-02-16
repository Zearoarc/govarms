    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="pieChart.js"></script>
    <script src="chart-bar-demo.js"></script>
    <script type="text/javascript" src="app.js"></script>
    <script>
    $(document).ready(function() {
        $('[id^="dept_new"]').on('change', function() {
            var departmentId = $(this).val();
            var rowId = $(this).attr('data-row-id') || ""; // Get the row ID
            var divisionId = $(this).attr('data-division-id') || "";
            console.log('Department ID:', departmentId); // Add this line
            console.log('Row ID:', rowId); // Add this line
            console.log('Division ID:', divisionId); // Add this line
            if (departmentId != '') { // Check if a department is selected
                $.ajax({
                type: 'POST',
                url: 'fetch_divisions.php',
                data: { departmentId: departmentId, rowId: rowId, divisionId: divisionId },
                success: function(data) {
                    console.log(data); // Log the response data to the console
                    $('#division_new' + rowId).html(data);
                    $('#division_new' + rowId).prop('disabled', false); // Enable division_new
                }
            });
        } else {
            $('#division_new').prop('disabled', true); // Disable division_new if no department is selected
            $('#division_new').html('<option value="" disabled selected>Select Division</option>'); // Reset division_new options
            }
        });
        $('[id^="dept_new"]').each(function() {
            if ($(this).val() != '') {
                $(this).trigger('change');
            }
        });
    });
    </script>
    </body>

    </html>