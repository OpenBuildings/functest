<div id="index">
	<div class="content">
		<ul class="subnav">
			<li><a class="navlink" id="navlink-1" title="Subpage Title 1" href="/test_functest/subpage1">Subpage 1 <img src="icon1.png" alt="icon 1"/> </a></li>
			<li><a class="navlink" id="navlink-2" title="Subpage Title 2" href="/test_functest/subpage2">Subpage 2 <img src="icon2.png" alt="icon 2"/> </a></li>
			<li><a class="navlink" id="navlink-3" title="Subpage Title 3" href="/test_functest/subpage3">Subpage 3 <img src="icon3.png" alt="icon 3"/> </a></li>
		</ul>
	</div>

	<div class="page">
		<p id="p-1">Lorem ipsum dolor sit amet, <em>consectetur</em> adipisicing elit, sed do eiusmod
		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

		<p id="p-2">Filtered! Ut <em>enim</em> ad minim veniam,
		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		consequat. Duis aute irure dolor in reprehenderit in <strong>voluptate velit</strong> esse
		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

		<p id="p-3" style="display:none;">
			This is a hidden text!
		</p>
	</div>

	<form action="/test_functest/contact" class="contact">
		<fieldset>
			<div class="row">
				<label for="email">Enter Email</label><input id="email" name="email" placeholder="This is your email" value="tom@example.com" />
			</div>
			<div class="row">
				<label for="name">Enter Name</label><input id="name" name="name" value="Tomas"/>
			</div>

			<div class="row">
				<label for="gender-1">Gender Male</label>
				<input id="gender-1" name="gender" value="male" type="radio" />
				<label for="gender-2">Gender Female</label>
				<input id="gender-2" name="gender" value="female" type="radio" checked />
			</div>

			<div class="row">
				<label for="notifyme">Enter Notify Me</label>
				<input id="notifyme" name="notifyme" type="checkbox" />
			</div>

			<div class="row">
				<label for="message">Enter Message</label>
				<textarea name="message" id="message" cols="30" rows="10">
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua.
				</textarea>
			</div>

			<div class="row">
				<label for="country">Enter Country</label>
				<select name="country" id="country">
					<option value="uk">United Kingdom</option>
					<option value="bulgaria">Bulgaria</option>
					<option value="us">United States</option>
				</select>
			</div>

			<div class="actions">
				<input id="submit" type="submit" value="Submit Item" name="submit input" />
				<button id="submit-btn" type="submit">Submit Button</button>
				<button id="submit-btn-icon" title="Image Title" type="submit"><img src="submit.png" alt="Submit Image"/></button>
			</div>

		</fieldset>
	</form>
</div>