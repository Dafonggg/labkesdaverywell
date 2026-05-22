import { createBrowserRouter } from 'react-router-dom';
import DashboardLayout from '../layouts/DashboardLayout';
import AuthLayout from '../layouts/AuthLayout';
import { ProtectedRoute } from './ProtectedRoute';

// Public Pages
import Login from '../pages/Login';
import Unauthorized from '../pages/Unauthorized';
import NotFound from '../pages/NotFound';

// Protected Pages (Admin & Shared Overview)
import AdminDashboard from '../features/dashboard/pages/AdminDashboard';
import Permohonan from '../pages/Permohonan';
import Registrasi from '../pages/Registrasi';
import Jadwal from '../pages/Jadwal';
import Pembayaran from '../pages/Pembayaran';
import Monitoring from '../pages/Monitoring';
import Arsip from '../pages/Arsip';

// Protected Pages (Petugas Lab)
import SampleMasuk from '../pages/SampleMasuk';
import Pengujian from '../pages/Pengujian';
import SampleDiuji from '../pages/SampleDiuji';

// Protected Pages (QC)
import QcVerifikasi from '../pages/QcVerifikasi';
import QcHistory from '../pages/QcHistory';

// Protected Pages (Analis)
import Laporan from '../pages/Laporan';

// Protected Pages (Kepala UPTD)
import Approval from '../pages/Approval';
import LaporanFinal from '../pages/LaporanFinal';

export const router = createBrowserRouter([
  // Public routes (e.g. Login)
  {
    element: <AuthLayout />,
    children: [
      { path: 'login', element: <Login /> }
    ]
  },

  // Base Dashboard Shell (Requires authentication)
  {
    path: '/',
    element: <ProtectedRoute />, // Verifies that user is logged in
    children: [
      {
        path: '/',
        element: <DashboardLayout />,
        children: [
          // 1. Dashboard Landing (Accessible to all roles)
          { path: '/', element: <AdminDashboard /> },

          // 2. Admin Role Protected Routes
          {
            element: <ProtectedRoute allowedRoles={['admin']} />,
            children: [
              { path: 'permohonan', element: <Permohonan /> },
              { path: 'registrasi', element: <Registrasi /> },
              { path: 'jadwal', element: <Jadwal /> },
              { path: 'pembayaran', element: <Pembayaran /> },
              { path: 'monitoring', element: <Monitoring /> },
              { path: 'arsip', element: <Arsip /> }
            ]
          },

          // 3. Petugas Lab Protected Routes
          {
            element: <ProtectedRoute allowedRoles={['petugas_lab']} />,
            children: [
              { path: 'sample-masuk', element: <SampleMasuk /> },
              { path: 'pengujian', element: <Pengujian /> },
              { path: 'sample-diuji', element: <SampleDiuji /> }
            ]
          },

          // 4. Quality Control Protected Routes
          {
            element: <ProtectedRoute allowedRoles={['qc']} />,
            children: [
              { path: 'qc/verifikasi', element: <QcVerifikasi /> },
              { path: 'qc/history', element: <QcHistory /> }
            ]
          },

          // 5. Analyst Protected Routes
          {
            element: <ProtectedRoute allowedRoles={['analis']} />,
            children: [
              { path: 'laporan', element: <Laporan /> }
            ]
          },

          // 6. Executive / Kepala UPTD Protected Routes
          {
            element: <ProtectedRoute allowedRoles={['kepala_uptd']} />,
            children: [
              { path: 'approval', element: <Approval /> },
              { path: 'laporan-final', element: <LaporanFinal /> }
            ]
          }
        ]
      }
    ]
  },

  // Error boundary pages
  { path: '/unauthorized', element: <Unauthorized /> },
  { path: '*', element: <NotFound /> }
]);
