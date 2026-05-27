<?php
    /** @var $user ?\App\Model\User */
?>

<div class="form-group">
    <label for="nickname">Nickname</label>
    <input type="text" id="nickname" name="user[nickname]" value="<?= $user ? $user->getNickname() : '' ?>">
</div>

<div class="form-group">
    <label for="email">Email</label>
    <input type="text" id="email" name="user[email]" value="<?= $user ? $user->getEmail() : '' ?>">
</div>

<div class="form-group">
    <label for="age">Age</label>
    <input type="number" id="age" name="user[age]" value="<?= $user ? $user->getAge() : '' ?>">
</div>

<div class="form-group">
    <label></label>
    <input type="submit" value="Submit">
</div>
