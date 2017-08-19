<html>
<head></head>
<body style="margin: 0; ;padding: 0;">
    <div style="text-align: center; padding: 30px; height: 360px; background: #808080;"><img src="image.php?action=<?php echo isset($_GET['action']) ? htmlspecialchars($_GET['action']) : ''; ?>" alt="" /></div>
    <ul>
        <li><a href="?action=">Original image</a></li>
        <li><a href="?action=enlarge">Enlarge</a></li>
        <li><a href="?action=reduce">Reduce</a></li>
        <li><a href="?action=square">Square</a></li>
        <li><a href="?action=rectangle-unproportional">Resize - Unproportional</a></li>
        <li><a href="?action=rectangle-contain">Resize - Contain</a></li>
        <li><a href="?action=rectangle-transparent">Resize - Contain - PNG (transparent)</a></li>
        <li><a href="?action=rectangle-contain-background">Resize - Contain - Background</a></li>
        <li><a href="?action=rectangle-cover">Resize - Cover</a></li>
        <li><a href="?action=compress">Compress</a></li>
        <li><a href="?action=filter-greyscale">Greyscale (filter)</a></li>
        <li><a href="?action=extreme-compress">Extreme compress</a></li>
        <li><a href="?action=webp">WebP</a></li>
        <li><a href="?action=webp-compressed">Compressed WebP</a></li>
    </ul>
</body>
</html>