<html>
    <head>
        <title> Add BRF </title>
    </head>
    <body>
        <h1> Add Bookstore Requisition Form</h1>
        <p> New Entry </p> <!-- Append INPUT -->
        <?php
            $total = 1;
        ?>
        <form action="" method="POST">

            <!-- APPEND THIS -->
            <input type="number" name="qty-{{ $total }}" placeholder="Quantity">
            <input type="description" name="desc-{{ $total }}" placeholder="Description">
            <p>Remove</p><br>

            <input type="submit" value="submit">
        </form>
    </body>
</html>