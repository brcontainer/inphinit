<!DOCTYPE html>
<html>
<head>
    <title><?php $title; ?></title>
</head>
<body>
    <h1><?php echo $title; ?></h1>
    <p>Http status: <?php echo $status; ?></p>
    <p>Path: <?php echo $path; ?></p>

    <?php if ($isRoute): ?>
        <p>Route: <?php echo $route; ?></p>
    <?php endif; ?>

    <p>Querystring: <?php echo $querystring; ?></p>

    <p>Is route: <?php echo $isRoute ? 'true' : 'false'; ?></p>
</body>
</html>