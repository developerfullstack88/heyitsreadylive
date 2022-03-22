<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
      @if($userData)
        <p>Hey {{ucfirst($userData->first_name)}} {{ucfirst($userData->last_name)}},</p>
      @endif
      <p style="margin-top:50px;">{{__('register.welcome_email_heading')}}<p>
      <p style="margin-top:30px;">{{__('register.welcome_email_excited__mission_p')}}</p>
      <p style="margin-top:50px;">{{__('register.welcome_email_PDF_attached_label')}}<p>
      <p style="margin-top:30px;">{{__('register.welcome_email_both_pdf_guide_label')}}</p>
      <p style="margin-top:30px;">{{__('register.if_you_have_questions_label')}}<a href="mailto:info@heyitsready.com">info@heyitsready.com</a></p>
      <p style="margin-top:50px;">{{__('register.sincerely_label')}},</p>
      <p>{{__('register.welcome_email_team_hey_its_ready_label')}}</p>
      <img src="{{asset('img/ipixup_background_image.png')}}" style="max-width:100px;margin-left:350px;"/>
  </body>
</html>
