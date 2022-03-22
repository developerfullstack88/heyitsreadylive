<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
      @if($userData)
        <p>Hey, {{ucfirst($userData->first_name)}} {{ucfirst($userData->last_name)}}</p>
      @endif
      <p>{{__('register.activate_email_great_decision_label')}}<p>
      <p>{{__('register.activate_email_create_password_label')}}</p>
      <p>{{__('register.sincerely_label')}},</p>
      <p>{{__('register.welcome_email_team_hey_its_ready_label')}}</p>
      <p>{{__('register.activate_email_link_label')}}</p>
      <a data-click-track-id="37" href="{{$userData->url}}" style="margin-top: 36px; -ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #ffffff; font-family: 'Postmates Std', 'Helvetica', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-size: 12px; font-smoothing: always; font-style: normal; font-weight: 600; letter-spacing: 0.7px; line-height: 48px; mso-line-height-rule: exactly; text-decoration: none;
      vertical-align: top; width: 770px; background-color: #00cc99; border-radius: 28px;
      display: block; text-transform: uppercase;text-align:center;" target="_blank">
                                              {{__('register.activate_email_click_here_label')}}
                                            </a>
      <img src="{{asset('img/ipixup_background_image.png')}}" style="max-width:100px;margin-left:300px;"/>
      <p><b>{{__('register.activate_email_our_mission_label')}}</b></p>
      <p>{{__('register.actiavte_email_enable_connect_label')}}</p>
      <p><b>{{__('register.activate_email_small_business_label')}}</b></p>
  </body>
</html>
