import React from 'react';
import { Link } from 'react-router-dom';
import { Plus, Package, TrendingUp, Users, Bell, Calendar, MapPin, Clock } from 'lucide-react';
import { useAuth } from '../contexts/AuthContext';

const RestaurantDashboard: React.FC = () => {
  const { user } = useAuth();

  const recentListings = [
    { id: 1, name: 'Fresh Sandwiches', quantity: '25 pieces', status: 'Active', expiry: '2 hours', location: 'Main Kitchen' },
    { id: 2, name: 'Pasta Salad', quantity: '3 containers', status: 'Matched', expiry: '4 hours', location: 'Cold Storage' },
    { id: 3, name: 'Bread Rolls', quantity: '50 pieces', status: 'Picked Up', expiry: 'Expired', location: 'Bakery Section' },
  ];

  const notifications = [
    { id: 1, message: 'New match found for your Pasta Salad listing', time: '5 min ago', type: 'match' },
    { id: 2, message: 'Pickup completed for Bread Rolls', time: '1 hour ago', type: 'pickup' },
    { id: 3, message: 'Fresh Sandwiches expires in 2 hours', time: '30 min ago', type: 'warning' },
  ];

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900">
            Welcome back, {user?.name}
          </h1>
          <p className="text-gray-600 mt-1">
            {user?.restaurantName} Dashboard
          </p>
        </div>

        {/* Quick Actions */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <Link
            to="/create-listing"
            className="bg-green-600 text-white p-6 rounded-lg hover:bg-green-700 transition-colors group"
          >
            <div className="flex items-center justify-between">
              <div>
                <p className="text-green-100">Create New</p>
                <p className="text-xl font-semibold">Food Listing</p>
              </div>
              <Plus className="h-8 w-8 group-hover:scale-110 transition-transform" />
            </div>
          </Link>

          <Link
            to="/manage-listings"
            className="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow group border-l-4 border-blue-500"
          >
            <div className="flex items-center justify-between">
              <div>
                <p className="text-gray-600">Manage</p>
                <p className="text-xl font-semibold text-gray-900">Listings</p>
              </div>
              <Package className="h-8 w-8 text-blue-500 group-hover:scale-110 transition-transform" />
            </div>
          </Link>

          <Link
            to="/tracking"
            className="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow group border-l-4 border-purple-500"
          >
            <div className="flex items-center justify-between">
              <div>
                <p className="text-gray-600">Track</p>
                <p className="text-xl font-semibold text-gray-900">Donations</p>
              </div>
              <MapPin className="h-8 w-8 text-purple-500 group-hover:scale-110 transition-transform" />
            </div>
          </Link>

          <Link
            to="/reports"
            className="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow group border-l-4 border-orange-500"
          >
            <div className="flex items-center justify-between">
              <div>
                <p className="text-gray-600">View</p>
                <p className="text-xl font-semibold text-gray-900">Reports</p>
              </div>
              <TrendingUp className="h-8 w-8 text-orange-500 group-hover:scale-110 transition-transform" />
            </div>
          </Link>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Main Content */}
          <div className="lg:col-span-2">
            {/* Stats Overview */}
            <div className="bg-white rounded-lg shadow-lg p-6 mb-8">
              <h2 className="text-xl font-semibold text-gray-900 mb-4">Overview</h2>
              <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div className="text-center p-4 bg-green-50 rounded-lg">
                  <div className="text-2xl font-bold text-green-600">156</div>
                  <div className="text-sm text-gray-600">Total Listings</div>
                </div>
                <div className="text-center p-4 bg-blue-50 rounded-lg">
                  <div className="text-2xl font-bold text-blue-600">89</div>
                  <div className="text-sm text-gray-600">Successful Matches</div>
                </div>
                <div className="text-center p-4 bg-purple-50 rounded-lg">
                  <div className="text-2xl font-bold text-purple-600">1,250</div>
                  <div className="text-sm text-gray-600">Meals Donated</div>
                </div>
                <div className="text-center p-4 bg-orange-50 rounded-lg">
                  <div className="text-2xl font-bold text-orange-600">3.2</div>
                  <div className="text-sm text-gray-600">Tons Saved</div>
                </div>
              </div>
            </div>

            {/* Recent Listings */}
            <div className="bg-white rounded-lg shadow-lg p-6">
              <div className="flex items-center justify-between mb-4">
                <h2 className="text-xl font-semibold text-gray-900">Recent Listings</h2>
                <Link to="/manage-listings" className="text-green-600 hover:text-green-700 text-sm font-medium">
                  View All
                </Link>
              </div>
              <div className="space-y-4">
                {recentListings.map((listing) => (
                  <div key={listing.id} className="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div className="flex items-center justify-between">
                      <div className="flex-1">
                        <h3 className="font-medium text-gray-900">{listing.name}</h3>
                        <div className="flex items-center space-x-4 mt-1 text-sm text-gray-600">
                          <span className="flex items-center">
                            <Package className="h-4 w-4 mr-1" />
                            {listing.quantity}
                          </span>
                          <span className="flex items-center">
                            <MapPin className="h-4 w-4 mr-1" />
                            {listing.location}
                          </span>
                          <span className="flex items-center">
                            <Clock className="h-4 w-4 mr-1" />
                            {listing.expiry}
                          </span>
                        </div>
                      </div>
                      <div className="ml-4">
                        <span className={`px-2 py-1 rounded-full text-xs font-medium ${
                          listing.status === 'Active' ? 'bg-green-100 text-green-800' :
                          listing.status === 'Matched' ? 'bg-blue-100 text-blue-800' :
                          listing.status === 'Picked Up' ? 'bg-gray-100 text-gray-800' :
                          'bg-red-100 text-red-800'
                        }`}>
                          {listing.status}
                        </span>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Sidebar */}
          <div className="space-y-6">
            {/* Notifications */}
            <div className="bg-white rounded-lg shadow-lg p-6">
              <div className="flex items-center justify-between mb-4">
                <h2 className="text-lg font-semibold text-gray-900">Notifications</h2>
                <Bell className="h-5 w-5 text-gray-500" />
              </div>
              <div className="space-y-3">
                {notifications.map((notification) => (
                  <div key={notification.id} className="border-l-4 border-blue-500 pl-3 py-2">
                    <p className="text-sm text-gray-900">{notification.message}</p>
                    <p className="text-xs text-gray-500 mt-1">{notification.time}</p>
                  </div>
                ))}
              </div>
            </div>

            {/* Quick Stats */}
            <div className="bg-white rounded-lg shadow-lg p-6">
              <h2 className="text-lg font-semibold text-gray-900 mb-4">Today's Activity</h2>
              <div className="space-y-4">
                <div className="flex items-center justify-between">
                  <span className="text-gray-600">Active Listings</span>
                  <span className="font-semibold text-green-600">8</span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-gray-600">Pending Matches</span>
                  <span className="font-semibold text-blue-600">3</span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-gray-600">Completed Pickups</span>
                  <span className="font-semibold text-purple-600">5</span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-gray-600">Meals Donated Today</span>
                  <span className="font-semibold text-orange-600">45</span>
                </div>
              </div>
            </div>

            {/* Quick Actions */}
            <div className="bg-gradient-to-br from-green-500 to-blue-500 rounded-lg shadow-lg p-6 text-white">
              <h2 className="text-lg font-semibold mb-4">Need Help?</h2>
              <p className="text-green-100 mb-4 text-sm">
                Our support team is here to help you maximize your impact.
              </p>
              <Link
                to="/contact"
                className="bg-white text-green-600 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-100 transition-colors inline-block"
              >
                Contact Support
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default RestaurantDashboard;