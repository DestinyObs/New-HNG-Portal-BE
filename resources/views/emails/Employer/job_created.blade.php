<!-- resources/views/emails/job-created.blade.php -->
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>New Job Created</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    /* Basic reset */
    body { margin: 0; padding: 0; background-color: #f5f7fb; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial; color: #34495e; }
    .container { max-width: 680px; margin: 28px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 6px 18px rgba(17,24,39,0.08); }
    .header { padding: 20px 28px; background: linear-gradient(90deg,#0ea5a4,#06b6d4); color: #fff; }
    .brand { font-size: 18px; font-weight: 700; letter-spacing: .2px; }
    .content { padding: 28px; }
    h1 { margin: 0 0 12px; font-size: 20px; color: #0f172a; }
    .meta { color: #6b7280; font-size: 13px; margin-bottom: 18px; }
    .status-pill { display: inline-block; padding: 6px 10px; font-size: 13px; border-radius: 999px; font-weight: 600; }
    .status-published { background: #dcfce7; color: #166534; }
    .status-unpublished { background: #fce7f3; color: #831843; }
    .description { white-space: pre-wrap; line-height: 1.55; color: #334155; margin-bottom: 20px; }
    .skills-wrap { margin-bottom: 20px; }
    .skill { display:inline-block; margin:6px 8px 0 0; padding:6px 10px; background:#eef2ff; color:#3730a3; font-weight:600; border-radius: 999px; font-size:13px; }
    .action { text-align:center; margin:24px 0; }
    .btn { background:#0ea5a4; color:#fff; text-decoration:none; padding:12px 20px; border-radius:8px; font-weight:700; display:inline-block; }
    .footer { padding:18px 28px; font-size:13px; color:#6b7280; background:#fbfdff; border-top:1px solid #eef2ff; }
    .muted { color:#94a3b8; font-size:13px; }

    /* Responsive */
    @media (max-width:520px){
      .container{ margin: 12px; }
      .content{ padding:18px; }
      .header{ padding:16px; }
    }
  </style>
</head>
<body>
  <div class="container" role="article" aria-label="Job created notification">
    <div class="header">
      <div class="brand">{{ config('app.name', 'Your App') }}</div>
    </div>

    <div class="content">
      <h1>New job saved: {{ $job->title ?? 'Untitled role' }}</h1>

      <div class="meta">
        <span>Created: {{ optional($job->created_at)->format('F j, Y \a\t g:ia') ?? now()->format('F j, Y \a\t g:ia') }}</span>
        &nbsp;•&nbsp;
        <span>Publication: 
          @if (isset($job->publication_status) && $job->publication_status === 'published')
            <span class="status-pill status-published">Published</span>
          @else
            <span class="status-pill status-unpublished">{{ $job->publication_status ?? 'Unpublished' }}</span>
          @endif
        </span>
      </div>

      <div class="description">
        {!! nl2br(e($job->description ?? 'No description provided.')) !!}
      </div>

      <div class="skills-wrap">
        <strong>Skills required</strong>
        <div style="margin-top:8px;">
          @if(!empty($job->skills) && count($job->skills))
            @foreach($job->skills as $skill)
              <span class="skill">{{ is_object($skill) ? ($skill->name ?? $skill->title ?? $skill->slug) : $skill }}</span>
            @endforeach
          @else
            <div class="muted" style="margin-top:8px;">No skills specified.</div>
          @endif
        </div>
      </div>

      <div class="action">
        @php
          // Use a provided URL variable or build one
          $url = $viewUrl ?? (isset($job->id) ? url("/jobs/{$job->id}") : url('/jobs'));
        @endphp

        <a href="{{ $url }}" class="btn" target="_blank" rel="noopener noreferrer">View job</a>
      </div>

      <div class="muted">
        This email was sent because a job listing was created or updated in your account.
      </div>
    </div>

    <div class="footer">
      <div><strong>{{ config('app.name', 'Your App') }}</strong></div>
      <div style="margin-top:6px;">Need help? Reply to this email or visit our support center.</div>
    </div>
  </div>
</body>
</html>
