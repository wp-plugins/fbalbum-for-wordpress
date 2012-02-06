<form id="binnash-fbalbum-form" name="binnash_fbalbum_form" method="post">    
    <div id="link-selector">
        <table>
            <tr>
                <td colspan="2">
                <?php
                if($notLoggedIn):?>
                You Are Not Authenticated with Facebook. <br/>
                Click  <a href='<?php echo $loginUrl;?>'>Here</a> To Authenticate.
                <?php else:?>                    
                    <select id="binnash-fbalbum-shortcode-selector">
                        <option value="0">Select one</option>
                        <?php foreach($albums as $key=>$value):?>
                        <option value="<?echo $key;?>"><?php echo $value;?></option>
                        <?php endforeach;?>
                    </select>                    
                <?php endif;?> 
                </td>
            </tr>           
            <tr>
                <td>
                    <div id="wp-link-cancel">
                        <a href="#" class="submitdelete deletion">Cancel</a>
                    </div>                    
                </td>
                <td>
                    <div id="wp-link-update">
                        <input type="submit" tabindex="100" value="Add" class="button-primary" id="wp-link-submit" name="wp-link-submit"/>
                    </div>                    
                </td>                
            </tr>            
        </table>
    </div>    
</form>