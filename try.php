<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Table Sample</title>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Include Bootstrap Table CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.22.1/bootstrap-table.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Sample Table Using Bootstrap Table</h2>

    <!-- Table element with the "table" and "table-bordered" classes -->
    <table id="sampleTable" class="table table-bordered"
           data-toggle="table"
           data-search="true"
           data-show-export="true"
           data-pagination="true"
           data-sortable="true"
           data-export-types="['csv', 'excel', 'pdf']">
        <thead>
            <tr>
                <th data-field="id" data-sortable="true">ID</th>
                <th data-field="name" data-sortable="true">Name</th>
                <th data-field="price" data-sortable="true">Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Product 1</td>
                <td>$10.00</td>
            </tr>
            <!-- Add more rows as needed -->
        </tbody>
    </table>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>



<!-- Include Bootstrap Table JS -->
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.22.1/bootstrap-table.min.js"></script>
    
<!-- Include Bootstrap Table Export extensions for export functionality -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.71/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.71/vfs_fonts.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/TableExport/5.2.0/js/tableexport.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.22.1/extensions/export/bootstrap-table-export.min.js"></script>
    



<!-- Initialize Bootstrap Table -->
<script>
    $(document).ready(function() {
        $('#sampleTable').bootstrapTable();
    });
</script>

</body>
</html>
