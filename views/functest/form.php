<html>
<head>
	<title>Form</title>
</head>
<body>
	<div class="body">
		<form action="/test_functest/form">

			<div id="hidden" style="display:none">
				Hidden
			</div>

			<div id="visible" style="display:block">
				<a id="visible-link" href="/test_functest/page1">Test</a>
			</div>

			<fieldset>
				<legend>Author</legend>
				<input type="hidden" name="id" value="10"/>

				<div class="row">
					<input name="name" value="Arthur"/>
				</div>
			</fieldset>

			<div class="row">
				<input type="hidden" name="post[id]" value="1"/>
			</div>
			<div class="row">
				<input name="post[title]" id="post_title" value="Title 1"/>
			</div>
			<div class="row">
				<input name="post[tag][name]" value="Red"/>
			</div>
			<div class="row">
				<input name="post[tag][rating]" value="20"/>
			</div>
			<div class="row">
				<input type="number" name="post[tag][quantity]" value="1"/>
			</div>
			<div class="row">
				<textarea name="post[body]" id="post_body" cols="30" rows="10">Lorem Ipsum</textarea>
			</div>

			<div class="row">
				<input type="radio" name="post[type]" value="big">
				<input type="radio" name="post[type]" value="small" checked>
				<input type="radio" name="post[type]" value="tiny">
			</div>

			<div class="row">
				<input type="checkbox" name="post[send]" value="">
				<input type="checkbox" name="post[send]" value="sendval" checked="checked">
			</div>
			<div class="row">
				<select name="post[category]" id="post_category">
					<optgroup label="Tech">
						<option value="sw">Software</option>
						<option value="hw" selected="selected">Hardware</option>
					</optgroup>
					<option value="other">Other</option>
				</select>
			</div>
			<div class="row">
				<select name="post[ads]" id="post_ads" multiple="multiple">
					<optgroup label="Main">
						<option value="banner">Banner</option>
						<option value="text" selected="selected">Text</option>
					</optgroup>
					<option value="affiliate" selected="selected">Affiliate</option>
				</select>
			</div>

			<div class="actions">
				<input id="submit" type="submit" value="Submit Item" name="submit_input" />
				<button id="submit-btn" type="submit">Submit Button</button>
			</div>
		</form>
	</div>
</body>
</html>