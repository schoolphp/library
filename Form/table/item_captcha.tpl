    <tr<?php if(!empty($this->content[$key]['error'])) {echo ' style="background-color:#FAA"';} ?>>
      <td><?php echo $this->content[$key]['title']; ?></td>
      <td>
        <img src="/skins/components/kcaptcha/index.php" alt=""><br>
        <input type="text" name="<?php echo $this->content[$key]['name']; ?>" value="" <?php echo $this->content[$key]['attrs']; ?> <?php echo $this->content[$key]['attrs']; ?>><br>
        <i><?php echo $this->content[$key]['text']; ?></i>
      </td>
      <td>
        <?php echo $this->content[$key]['error']; ?>
      </td>
    </tr>
