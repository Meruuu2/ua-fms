@extends('layouts.app')

@section('content')
<style>
    /* ── REFINED MONOCHROME LOGIN UI ── */

    /* Full-screen centering container */
    .login-page-wrapper {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      box-sizing: border-box;
      padding: 24px;
    }

    .login-container {
      width: 100%;
      max-width: 440px;
    }

    /* Back Button Styling (Above the card) */
    .back-link {
      display: inline-flex;
      align-items: center;
      font-size: 14px;
      color: #000000;
      text-decoration: none;
      margin-bottom: 24px;
      transition: all 0.2s ease;
      font-weight: 500;
      background-color: #ffffff;
      padding: 8px 16px;
      border: 1px solid #E6E6E6;
      border-radius: 4px;
    }

    .back-link:hover {
      background-color: #f5f5f5;
      border-color: #d4d4d4;
    }

    /* Minimalist Card */
    .login-card {
      background: #ffffff;
      padding: 60px 48px; /* Adjust based on image perception */
      border: 1px solid #E6E6E6; /* Light gray border */
      box-shadow: 0 10px 30px rgba(0,0,0,0.02);
      box-sizing: border-box;
    }

    /* Typography */
    .login-header {
      text-align: center;
      margin-bottom: 32px;
    }

    .login-header h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 32px; /* Large, bold serif font */
      font-weight: 400;
      margin: 0 0 8px 0;
      color: #111111;
    }

    /* "— Admin —" subtitle with line separators */
    .login-admin-tag {
      font-size: 14px;
      color: #808080;
      margin-bottom: 8px;
      text-transform: uppercase; /* Match the visual */
      letter-spacing: 0.1em; /* Add visual space */
    }

    .login-header p {
      font-size: 14px;
      color: #808080;
      margin: 0;
    }

    /* Form Elements */
    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 8px;
      color: #111111;
    }

    /* Base form control style (Light Border) */
    .form-control {
      width: 100%;
      padding: 12px 16px;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      color: #111111;
      background: #ffffff;
      border: 1px solid #E6E6E6; /* Light gray border base */
      box-sizing: border-box;
      transition: all 0.2s ease;
      outline: none;
    }

    .form-control:focus {
      border-color: #111111;
    }

    /* Specific override to match input borders in the target image */
    #email.form-control {
      border: 1px solid #111111; /* Black border for Email */
    }
    #password.form-control {
      border: 1px solid #E6E6E6; /* Light gray border for Password */
    }

    .login-btn {
      width: 100%;
      padding: 14px;
      margin-top: 12px;
      background-color: #111111;
      color: #ffffff;
      border: none;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .login-btn:hover {
      background-color: #333333;
    }

    /* Laravel Error Alert Styling */
    .fms-alert-error {
      background-color: #fff1f0;
      border: 1px solid #ffa39e;
      color: #cf1322;
      padding: 12px;
      font-size: 13px;
      margin-bottom: 24px;
      text-align: center;
    }
</style>

<div class="login-page-wrapper">
  <div class="login-container">

    <a href="{{ url('/') }}" class="back-link">&#8592; Back to home</a>

    <div class="login-card">
      <div class="login-header">
        <h1>Log In</h1>
        <div class="login-admin-tag">Facility Management Portal</div>
        <p>Use your assigned UA email and password to sign in.</p>
      </div>

      @if ($errors->any())
          <div class="fms-alert-error">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="form-group">
          <label for="email">Email Address</label>
          <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
            autofocus
            class="form-control"
            placeholder="Enter your email"
          >
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input
            id="password"
            type="password"
            name="password"
            required
            class="form-control"
            placeholder="Enter your password"
          >
        </div>

        <button type="submit" class="login-btn">
          Log In
        </button>
      </form>

    </div>
  </div>
</div>
@endsection

