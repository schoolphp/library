    <tr<?php if(!empty($this->content[$key]['error'])) {echo ' style="background-color:#900"';} ?>>
      <td colspan="3">
        <div align="center">
          <input type="submit" name="<?php echo $this->content[$key]['name']; ?>" value="<?php echo $this->content[$key]['value']; ?>" <?php echo $this->content[$key]['attrs']; ?>><br>
        </div>
      </td>
    </tr>
