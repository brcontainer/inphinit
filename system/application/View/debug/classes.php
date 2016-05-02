<div style="border: 1px #c0c0c0 solid; padding: 5px; margin: 5px;">
<h1>Classes</h1>
<?php foreach ($classes as $key => $value): ?>
    <div style="border: 1px #c0c0c0 solid; padding: 5px; margin: 5px;">
        <h2><?php echo $key; ?>:</h2>
        <pre><?php print_r($value); ?></pre>
    </div>
<?php endforeach; ?>
</div>