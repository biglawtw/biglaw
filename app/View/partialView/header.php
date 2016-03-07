<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?=url('index' , 'spider')?>">biglaw admin</a>
        </div>
        <?php
            if($_SESSION['islogin']){
        ?>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="<?=url('logout' , 'spider')?>">登出</a></li>
        </ul>
        <?php
            }
        ?>
    </div>
</div>