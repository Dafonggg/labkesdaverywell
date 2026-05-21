import React from 'react';
import { Outlet, Navigate } from 'react-router-dom';
import { useAuthStore } from '../stores/auth';

const AuthLayout: React.FC = () => {
  const { isAuthenticated } = useAuthStore();

  // If already authenticated, redirect to root dashboard immediately
  if (isAuthenticated) {
    return <Navigate to="/" replace />;
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-cream-bg relative overflow-hidden font-body-md text-on-surface">
      {/* Dynamic Background Gradients */}
      <div className="absolute top-[-20%] left-[-10%] w-[60%] h-[60%] rounded-full bg-primary/10 blur-[120px] pointer-events-none" />
      <div className="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-primary-container/20 blur-[100px] pointer-events-none" />

      {/* Main Container */}
      <div className="w-full max-w-md p-6 relative z-10">
        <Outlet />
      </div>
    </div>
  );
};

export default AuthLayout;
