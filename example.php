<?php

// import environment varialbes & input data
$store = bootstrap();

// body of program: processing something here
// .....

// supposes the store is output...
echo json_encode($store, JSON_PRETTY_PRINT);

// -------------------------------------------------------- local functions

function bootstrap() {
  $store = array();

  foreach (array("OPWIRE_REQUEST", "OPWIRE_SETTING") as $envName) {
    if (array_key_exists($envName, $_SERVER)) {
      $data = $_SERVER[$envName];
      if (is_string($data) && strlen($data) > 0) {
        $store[$envName] = json_decode($data, true);
      }
    }
  }

  $input = "";
  while($line = fgets(STDIN)){
    $input .= $line;
  }

  if (is_string($input) && strlen($input) > 0) {
    $store["input"] = $input;
    $inputJSON = json_decode($input, true);
    switch (json_last_error()) {
      case JSON_ERROR_NONE:
        $store["input"] = $inputJSON;
      break;
      case JSON_ERROR_DEPTH:
        fwrite(STDERR, "Maximum stack depth exceeded\n");
      break;
      case JSON_ERROR_STATE_MISMATCH:
        fwrite(STDERR, "Underflow or the modes mismatch\n");
      break;
      case JSON_ERROR_CTRL_CHAR:
        fwrite(STDERR, "Unexpected control character found\n");
      break;
      case JSON_ERROR_SYNTAX:
        fwrite(STDERR, "Syntax error, malformed JSON\n");
      break;
      case JSON_ERROR_UTF8:
        fwrite(STDERR, "Malformed UTF-8 characters\n");
      break;
      default:
        fwrite(STDERR, "Unknown error\n");
      break;
    }
  }

  return $store;
}
?>
