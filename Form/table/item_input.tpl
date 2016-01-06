    <tr<?php if(!empty($this->content[$key]['error'])) {echo ' style="background-color:#FAA"';} ?>>
      <td><?php echo $this->content[$key]['title']; ?></td>
      <td>
        <input type="<?php echo $this->content[$key]['type']; ?>" name="<?php echo $this->content[$key]['name']; ?>" value="<?php echo $this->content[$key]['value']; ?>" <?php echo $this->content[$key]['attrs']; ?>>
        <?php if(!empty($this->content[$key]['text'])) { ?><br><i><?php echo $this->content[$key]['text']; ?></i><?php } ?>
      </td>
      <td>
        <?php echo $this->content[$key]['error']; ?>
      </td>
    </tr>
