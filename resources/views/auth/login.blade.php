<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('partials.head')
</head>
<body>
<div class="sm-login">
    <div class="sm-card sm-login-card shadow-sm">
        <div class="text-center mb-4">
            <div class="sm-logo" style="color: var(--sm-navy)">SUPER<span>MAX</span></div>
            <div class="text-muted small mt-2">للصناعات المعدنية · نظام أوامر التشغيل</div>
        </div>

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">اسم المستخدم</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" dir="ltr"
                       @class(['form-control', 'is-invalid' => $errors->has('username')]) autocomplete="username" autofocus required>
                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <input type="password" id="password" name="password" dir="ltr"
                       @class(['form-control', 'is-invalid' => $errors->has('password')]) autocomplete="current-password" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                <label class="form-check-label" for="remember">تذكرني</label>
            </div>
            <button type="submit" class="btn btn-sm-primary w-100 py-2">تسجيل الدخول</button>
        </form>

        <div class="text-center text-muted mt-4" style="font-size: .72rem">
            الإصدار {{ config('app.version') }} · تصميم وتطوير {{ config('app.author') }}
        </div>
    </div>
</div>
</body>
</html>
