<?php
  function datum_format($datum) {
    return date("d.m.Y. h:i A", strtotime($datum));
  }
?>
