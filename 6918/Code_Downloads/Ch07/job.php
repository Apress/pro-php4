<form action="job.php" method="POST">

    <div align="center"><center><table border="1" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td width="25%">Your Full Name</td>
        <td width="75%"><input type="text" size="20"
        name="name"></td>
    </tr>
    <tr>
        <td width="25%">Your Address</td>
        <td width="75%"><textarea name="address" rows="2"
        cols="20"></textarea></td>
    </tr>
    <tr>
        <td>Gender</td>
        <td><input type="radio" checked name="gender"
            value="Male">Male <input type="radio" name="gender"
            value="Female">Female</td>
    </tr>
    <tr>
        <td>Would like e-mail notification?</td>
        <td><input type="checkbox" checked name="email_me"
            value="Yes"></td>
    </tr>
    <tr>
        <td>Cities where I can work</td>
        <td><select name="pref_cities[]" multiple size="3">
            <option>Nagpur</option>
            <option>Mumbai</option>
            <option>Bangalore</option>
            <option>Chennai</option>
            <option>Kolkatta</option>
        </select></td>
    </tr>
    </table></center></div>
    <p align="center"><input type="submit" name="Submit" value="Submit"> 
    <input type="reset" name="Reset" value="Reset"></p>
</form>

<div align="center"><center><table border="1" cellpadding="0"
    cellspacing="0" width="100%">
    <tr>
        <td width="25%">Your Full Name</td>
        <td width="75%"><?php echo $name ?></td>
    </tr>
    <tr>
        <td width="25%">Your Address</td>
        <td width="75%"><?php echo nl2br($address) ?></td>
    </tr>
    <tr>
        <td>Gender</td>
        <td><?php echo $gender ?></td>
    </tr>
    <tr>
        <td>Would like e-mail notification?</td>
        <td>
            <?php if($email_me=="Yes"){ echo $email_me; }else{ echo "No"; }?>
        </td>
    </tr>
    <tr>
        <td>Cities where I can work</td>
        <td>
            <?php $counter=0;
            while($counter < count($pref_cities)){
                echo $pref_cities[$counter]."<br>";
                $counter++;
            }
            ?>
        </td>
    </tr>
</table>
