<form action="" method="post">
    <div class="row">
        <div class="col-12 col-xl-6" style="margin-bottom: 20px">
            <h3>SESSION:</h3>
            <pre>
                <?php print_r($_SESSION); ?>
            </pre>
            <input type="submit" name="delete_session" value="delete session" class="btn btn-primary">
        </div>
        <div class="col-12 col-xl-6">
            <h3>COOKIE:</h3>
            <pre>
                <?php print_r($_COOKIE); ?>
            </pre>
            <input type="submit" name="delete_cookie" value="delete cookie" class="btn btn-primary">
        </div>
    </div>
</form>

<style>
    pre {
        border: 1px solid;
        padding: 10px;
        border-radius: 5px;
        background-color: #eeeeee;
    }
    .btn {
        cursor: pointer;
    }
</style>