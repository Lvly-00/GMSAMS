import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { useState } from 'react';
import { Eye, EyeOff } from 'lucide-react';
import { useMutation } from '@tanstack/react-query';
import { useNavigate } from 'react-router-dom';
import { toast } from 'sonner';
import { login as apiLogin } from '@/api/auth';
import { useAuthStore } from '@/store/authStore';
import { Button, Card, Label } from '@/components/ui';

const schema = z.object({
  login: z.string().min(1, 'Username or email is required'),
  password: z.string().min(1, 'Password is required'),
  remember: z.boolean().optional(),
});

export default function LoginPage() {
  const navigate = useNavigate();
  const setAuth = useAuthStore((s) => s.setAuth);
  const [showPassword, setShowPassword] = useState(false);

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm({
    resolver: zodResolver(schema),
    defaultValues: { remember: false },
  });

  const mutation = useMutation({
    mutationFn: apiLogin,

    onSuccess: (data) => {
      setAuth(data.token, data.user);
      toast.success(data.message || 'Welcome back!');
      navigate(data.redirect_to || '/admin/dashboard');
    },

    onError: (err) => {
      const msg =
        err?.response?.data?.message ||
        err?.response?.data?.errors?.login?.[0] ||
        err.message ||
        'Login failed';

      toast.error(msg);
    },
  });

  return (
    <div className="min-h-screen w-full bg-gradient-to-br from-slate-100 via-slate-200 to-slate-100 flex items-center justify-center p-6">
      
      {/* Main Card */}
      <Card className="w-full max-w-md p-8 shadow-xl rounded-2xl border border-slate-200 bg-white">
        
        {/* Header */}
        <div className="mb-8 text-center">
          <h1 className="text-3xl font-bold tracking-tight text-slate-800">
            GMSAMS
          </h1>
          <p className="text-sm text-slate-500 mt-1">
            ATEC Technological College Apalit, Inc.
          </p>
        </div>

        {/* Form */}
        <form
          className="space-y-5"
          onSubmit={handleSubmit((v) => mutation.mutate(v))}
        >
          {/* Login */}
          <div className="space-y-1">
            <Label className="text-sm font-medium">Username or Email</Label>
            <input
              {...register('login')}
              className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-slate-400"
              placeholder="Enter username or email"
            />
            {errors.login && (
              <p className="text-xs text-red-500">{errors.login.message}</p>
            )}
          </div>

          {/* Password */}
          <div className="space-y-1">
            <Label className="text-sm font-medium">Password</Label>
            <div className="relative">
              <input
                type={showPassword ? 'text' : 'password'}
                {...register('password')}
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-slate-400"
                placeholder="••••••••"
              />
              <button
                type="button"
                onClick={() => setShowPassword(!showPassword)}
                className="absolute right-3 top-2.5 text-slate-500 hover:text-slate-700"
              >
                {showPassword ? <EyeOff size={16} /> : <Eye size={16} />}
              </button>
            </div>
            {errors.password && (
              <p className="text-xs text-red-500">{errors.password.message}</p>
            )}
          </div>

          {/* Remember */}
          <div className="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" {...register('remember')} />
            <span>Remember me</span>
          </div>

          {/* Button */}
          <Button
            type="submit"
            className="w-full rounded-lg py-2 text-white bg-slate-900 hover:bg-slate-800 transition"
            disabled={mutation.isPending}
          >
            {mutation.isPending ? 'Signing in...' : 'Login'}
          </Button>
        </form>

        {/* Footer hint */}
        <p className="mt-6 text-center text-xs text-slate-400">
          Secure access for authorized users only
        </p>
      </Card>
    </div>
  );
}