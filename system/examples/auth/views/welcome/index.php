<div class="container">
  <pre>
Actions before login
	join - Send email for registration
		An error will occur if the same email address that is currently valid is passed.

	register - Register an actor
		An error will occur if the token does not exist.
		An error occurs if the token is before the successful token.
		An error will occur if the token has expired.
		Move to the login page.

	login - Login
		Move to the corresponding actor page.

	change_email_verify - Change email address
		An error will occur if the token does not exist.
		An error occurs if the token is before the successful token.
		An error will occur if the token has expired.
		Move to the login page.

	forgot_password - Send email for password reset

	reset_password - Reset password
		An error will occur if the token does not exist.
		An error occurs if the token is before the successful token.
		An error will occur if the token has expired.
		Move to the login page.


Actions after login
	unregister - Unregister
		Logout.
		Moves to the guest actor page.

	logout - Logout
		Moves to the guest actor page.

	change_email - Send email for changing email address

	change_password - Change password
		Logout.
		Moves to the guest actor page.
  </pre>
</div>
