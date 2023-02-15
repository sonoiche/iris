<style>html,body { padding: 0; margin:0; }</style>
<div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
		<tbody>
			<tr>
				<td align="left" valign="center">
					<div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
						<!--begin:Email content-->
						<div style="padding-bottom: 30px; font-size: 17px;">
							<strong>IRIS Online Password Reset</strong>
						</div>
						<div style="padding-bottom: 30px">Hi {{ $user->fname }}, You recently requested to reset the password for your IRIS Online account. Click the button below to proceed. If you did not request a password reset, please ignore this email.</div>
						<div style="padding-bottom: 40px; text-align:center;">
							<a href="{{ config('app.url') }}/auth/password/reset/{{ $user->invite_token }}" rel="noopener" style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#333;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle" target="_blank">Reset Password</a>
						</div>
						<div style="padding-bottom: 10px">Kind regards,
						<br>IRIS Online. 
						<tr>
							<td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
								<p>Copyright &copy; <a href="https://irisonlie.app" rel="noopener" target="_blank">IRIS Online</a>.
                                </p>
							</td>
						</tr></br></div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>