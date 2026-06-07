import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';
import { cn } from '@/lib/utils';

export function Button({ className, variant = 'primary', children, ...props }) {
  const variants = {
    primary: 'bg-primary text-primary-foreground hover:bg-blue-800',
    outline: 'border border-slate-300 bg-white hover:bg-slate-50',
    danger: 'bg-red-600 text-white hover:bg-red-700',
    ghost: 'hover:bg-slate-100',
  };

  return (
    <button
      className={cn(
        'inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-medium transition disabled:opacity-50',
        variants[variant],
        className,
      )}
      {...props}
    >
      {children}
    </button>
  );
}

export function Input({ className, ...props }) {
  return (
    <input
      className={cn(
        'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary',
        className,
      )}
      {...props}
    />
  );
}

export function Label({ className, children, ...props }) {
  return (
    <label className={cn('mb-1 block text-sm font-medium text-slate-700', className)} {...props}>
      {children}
    </label>
  );
}

export function Card({ className, children }) {
  return <div className={cn('rounded-lg border bg-white p-6 shadow-sm', className)}>{children}</div>;
}

export function Badge({ className, children, variant = 'default' }) {
  const variants = {
    default: 'bg-slate-100 text-slate-700',
    success: 'bg-green-100 text-green-800',
    warning: 'bg-yellow-100 text-yellow-800',
    danger: 'bg-red-100 text-red-800',
  };
  return (
    <span className={cn('inline-flex rounded-full px-2 py-0.5 text-xs font-medium', variants[variant], className)}>
      {children}
    </span>
  );
}

export function Skeleton({ className }) {
  return <div className={cn('animate-pulse rounded-md bg-slate-200', className)} />;
}

export function Select({ className, children, ...props }) {
  return (
    <select
      className={cn(
        'flex h-10 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary',
        className,
      )}
      {...props}
    >
      {children}
    </select>
  );
}
