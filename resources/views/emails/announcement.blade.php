<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #333;">
    <h2>{{ $announcement->title }}</h2>
    
    <p>Hi {{ $recipient->name }},</p>
    
    <p>You have a new announcement from 
        <strong>
            {{ $sender->name ?? 'eMindTutor Admin' }}
        </strong>:
    </p>
    
    <div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #4A90E2; margin: 20px 0;">
        {!! nl2br(e($announcement->message)) !!}
    </div>

    <p>Please log in to your dashboard to see more details.</p>
    
    <hr>
    <p style="font-size: 0.8em; color: #777;">
        Sent by {{ $sender->name }} via <strong>eMindTutor</strong>.
    </p>
</body>
</html>