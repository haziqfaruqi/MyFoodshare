import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';

// Public Components
import PublicNavbar from './components/public/PublicNavbar';
import Homepage from './pages/public/Homepage';
import AboutUs from './pages/public/AboutUs';
import Contact from './pages/public/Contact';
import Login from './pages/auth/Login';
import Register from './pages/auth/Register';
import NotFound from './pages/public/NotFound';

// Restaurant Components
import RestaurantNavbar from './components/restaurant/RestaurantNavbar';
import RestaurantDashboard from './pages/restaurant/RestaurantDashboard';
import CreateListing from './pages/restaurant/CreateListing';
import ManageListings from './pages/restaurant/ManageListings';
import RestaurantMatching from './pages/restaurant/RestaurantMatching';
import RestaurantTracking from './pages/restaurant/RestaurantTracking';
import RestaurantReports from './pages/restaurant/RestaurantReports';
import RestaurantProfile from './pages/restaurant/RestaurantProfile';

// Admin Components
import AdminNavbar from './components/admin/AdminNavbar';
import AdminDashboard from './pages/admin/AdminDashboard';
import UserManagement from './pages/admin/UserManagement';
import AdminMatching from './pages/admin/AdminMatching';
import AdminTracking from './pages/admin/AdminTracking';
import AdminReports from './pages/admin/AdminReports';
import SystemSettings from './pages/admin/SystemSettings';
import IssueManagement from './pages/admin/IssueManagement';

import { AuthProvider } from './contexts/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';

function App() {
  return (
    <AuthProvider>
      <Router>
        <div className="min-h-screen bg-gray-50">
          <Routes>
            {/* Public Routes */}
            <Route path="/" element={
              <>
                <PublicNavbar />
                <Homepage />
              </>
            } />
            <Route path="/about" element={
              <>
                <PublicNavbar />
                <AboutUs />
              </>
            } />
            <Route path="/contact" element={
              <>
                <PublicNavbar />
                <Contact />
              </>
            } />
            <Route path="/login" element={
              <>
                <PublicNavbar />
                <Login />
              </>
            } />
            <Route path="/register" element={
              <>
                <PublicNavbar />
                <Register />
              </>
            } />

            {/* Restaurant Owner Routes */}
            <Route path="/restaurant/*" element={
              <ProtectedRoute allowedRoles={['restaurant_owner']}>
                <RestaurantNavbar />
                <Routes>
                  <Route path="dashboard" element={<RestaurantDashboard />} />
                  <Route path="create-listing" element={<CreateListing />} />
                  <Route path="manage-listings" element={<ManageListings />} />
                  <Route path="matching" element={<RestaurantMatching />} />
                  <Route path="tracking" element={<RestaurantTracking />} />
                  <Route path="reports" element={<RestaurantReports />} />
                  <Route path="profile" element={<RestaurantProfile />} />
                </Routes>
              </ProtectedRoute>
            } />

            {/* Admin Routes */}
            <Route path="/admin/*" element={
              <ProtectedRoute allowedRoles={['admin']}>
                <AdminNavbar />
                <Routes>
                  <Route path="dashboard" element={<AdminDashboard />} />
                  <Route path="users" element={<UserManagement />} />
                  <Route path="matching" element={<AdminMatching />} />
                  <Route path="tracking" element={<AdminTracking />} />
                  <Route path="reports" element={<AdminReports />} />
                  <Route path="settings" element={<SystemSettings />} />
                  <Route path="issues" element={<IssueManagement />} />
                </Routes>
              </ProtectedRoute>
            } />

            {/* 404 Route */}
            <Route path="*" element={
              <>
                <PublicNavbar />
                <NotFound />
              </>
            } />
          </Routes>
        </div>
      </Router>
    </AuthProvider>
  );
}

export default App;