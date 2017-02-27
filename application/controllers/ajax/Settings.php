<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

	protected $json_response = array();

	function __construct()
	{
		parent::__construct();
	}

	function item( $item_id = 0 )
	{
		echo 'tere - ' . $item_id;
		echo '<form class="bd-example">
  <fieldset>
	<legend>Example legend</legend>

	<p>
	  <label for="input">Example input</label>
	  <input id="input" placeholder="Example input" type="text">
	</p>

	<p>
	  <label for="select">Example select</label>
	  <select id="select">
		<option value="">Choose...</option>
		<optgroup label="Option group 1">
		  <option value="">Option 1</option>
		  <option value="">Option 2</option>
		  <option value="">Option 3</option>
		</optgroup>
		<optgroup label="Option group 2">
		  <option value="">Option 4</option>
		  <option value="">Option 5</option>
		  <option value="">Option 6</option>
		</optgroup>
	  </select>
	</p>

	<p>
	  <label>
		<input value="" type="checkbox">
		Check this checkbox
	  </label>
	</p>

	<p>
	  <label>
		<input name="optionsRadios" id="optionsRadios1" value="option1" checked="" type="radio">
		Option one is this and that
	  </label>
	  <label>
		<input name="optionsRadios" id="optionsRadios2" value="option2" type="radio">
		Option two is something else that is also super long to demonstrate the wrapping of these fancy form controls.
	  </label>
	  <label>
		<input name="optionsRadios" id="optionsRadios3" value="option3" disabled="" type="radio">
		Option three is disabled
	  </label>
	</p>

	<p>
	  <label for="textarea">Example textarea</label>
	  <textarea id="textarea" rows="3"></textarea>
	</p>

	<p>
	  <label for="time">Example temporal</label>
	  <input id="time" type="datetime-local">
	</p>

	<p>
	  <label for="output">Example output</label>
	  <output name="result" id="output">100</output>
	</p>

	<p>
	  <button type="submit">Button submit</button>
	  <input value="Input submit button" type="submit">
	  <input value="Input button" type="button">
	</p>

	<p>
	  <button type="submit" disabled="">Button submit</button>
	  <input value="Input submit button" disabled="" type="submit">
	  <input value="Input button" disabled="" type="button">
	</p>
  </fieldset>
</form>';
	}
}
