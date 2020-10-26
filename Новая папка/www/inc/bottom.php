<div style="clear:both"></div>
</div>
</div>
<div id="footer" class="fullWidth">
<div class="content">
<p id="footerMenu">
Онлайн: <?php echo mysqli_num_rows(mysqli_query($connect_db, "SELECT id FROM t_online")); ?> чел. © 2016 <?php echo SITE; ?> | <a href="https://payeer.com" title="Завести кошелёк" target="_blank">Payeer</a> | <a href="http://webupper.ru" title="Разработка сайтов и PHP скриптов" target="_blank">WebUpper CMS</a>
</p>
</div>
</div>
</div>
</body>
</html>