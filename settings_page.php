<h2 > Settings</h2>
<div id="message" class="updated below-h2"><?php echo $msg;?></div>
<form method="post">
    <div class="postbox">	               
    <h3> General</h3>
        <div class="inside bg">
        <table class="form-table">
            <tr>
                <td>Facebook APP ID</td>
                <td><input type="text" size="50" name="fb_app_id" value="<?php echo $fields['fb_app_id']; ?>" />
                    <br/> You Can Collect Facebook APP ID From <a target="_blank" href="https://developers.facebook.com/apps">Here</a></td>
            </tr>
            <tr>
                <td>Facebook Secret</td>
                <td><input type="text" size="50" name="fb_secret_id" value="<?php echo $fields['fb_secret_id']; ?>" />
                    <br/> You Can Collect Facebook Secret<a target="_blank" href="https://developers.facebook.com/apps">Here</a></td>
            </tr>

        </table>
        </div>
    </div>
    <div class="postbox">	               
    <h3> Facebook Authentication</h3>
        <div class="inside bg">
        <table class="form-table">
            <tr>
                <td>Redirect URL</td>
                <td><input readonly="readonly" type="text" size="100" value="<?php echo $redirect_url; ?>" />
                    <br/>Facebook Will Redirect To This URL After Successful Autnenticaton. You Need To Add This URL
                    <a target="_blank" href="https://developers.facebook.com/apps">Here</a>.<br/>On The Facebook App Page,
                    Scroll Down And You'll See Section Called "Select how your app integrates with Facebook". <br/>
                    Click On The "Website" Row And The Above URL.
                </td>
            </tr>                        
            <tr>
                <td>Authenticate</td>
                <td>
                    <?php if(isset($user)):?>
                    You are logged in as <?php echo $user['name']; ?>. <a href="<?php echo $logoutUrl;?>">Logout</a>.
                    <?php else:?>
                    Please <a href="<?php echo $loginUrl;?>">login</a> to authenticate.
                    <?php endif;?>
                </td>                
            </tr>            
        </table>
        </div>
    </div>
    <p class="submit">
        <input type="submit" name="op_edit_settngs" value="Update &raquo;" /> 
    </p>   
</form> 
