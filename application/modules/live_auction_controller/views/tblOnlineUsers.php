<fieldset>
   <legend>Online Users</legend>
    <table>
      <thead>
        <tr>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Email</th>
          <th>Mobile</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if($online_users){
          foreach ($online_users as $key => $value) {
            ?>
            <tr>
              <td><?= $value['fname']; ?></td>
              <td><?= $value['lname']; ?></td>
              <td><?= $value['email']; ?></td>
              <td><?= $value['mobile']; ?></td>
            </tr>
            <?php
          }
        } ?>
      </tbody>
    </table>
</fieldset>