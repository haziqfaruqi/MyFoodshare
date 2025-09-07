import React, { useState } from 'react';
import { MapPin, Users, Clock, CheckCircle, XCircle, Phone, Mail, Filter, AlertTriangle } from 'lucide-react';

const AdminMatching: React.FC = () => {
  const [filter, setFilter] = useState('all');
  const [selectedRegion, setSelectedRegion] = useState('all');

  const globalMatches = [
    {
      id: 1,
      restaurant: 'Golden Spoon',
      foodName: 'Fresh Sandwiches',
      quantity: '25 pieces',
      location: 'Downtown',
      region: 'Central',
      expiryTime: '6 hours',
      matches: [
        {
          id: 1,
          organizationName: 'Hope Community Center',
          contactPerson: 'Sarah Johnson',
          phone: '+1 234-567-8900',
          email: 'sarah@hopecenter.org',
          distance: '0.8 miles',
          capacity: '50 people',
          rating: 4.8,
          status: 'pending',
          priority: 'high'
        },
        {
          id: 2,
          organizationName: 'City Homeless Shelter',
          contactPerson: 'Mike Chen',
          phone: '+1 234-567-8901',
          email: 'mike@cityshelter.org',
          distance: '1.2 miles',
          capacity: '100 people',
          rating: 4.6,
          status: 'approved',
          priority: 'medium'
        }
      ]
    },
    {
      id: 2,
      restaurant: 'Pizza Corner',
      foodName: 'Pasta Salad',
      quantity: '3 containers',
      location: 'Midtown',
      region: 'North',
      expiryTime: '8 hours',
      matches: [
        {
          id: 3,
          organizationName: 'Family Support Services',
          contactPerson: 'Lisa Rodriguez',
          phone: '+1 234-567-8902',
          email: 'lisa@familysupport.org',
          distance: '0.5 miles',
          capacity: '30 families',
          rating: 4.9,
          status: 'pending',
          priority: 'high'
        }
      ]
    },
    {
      id: 3,
      restaurant: 'Bread Basket',
      foodName: 'Bread Rolls',
      quantity: '50 pieces',
      location: 'Uptown',
      region: 'South',
      expiryTime: '4 hours',
      matches: [
        {
          id: 4,
          organizationName: 'Senior Care Center',
          contactPerson: 'Robert Kim',
          phone: '+1 234-567-8903',
          email: 'robert@seniorcare.org',
          distance: '2.1 miles',
          capacity: '75 seniors',
          rating: 4.7,
          status: 'flagged',
          priority: 'low'
        }
      ]
    }
  ];

  const regions = ['all', 'Central', 'North', 'South', 'East', 'West'];

  const filteredMatches = globalMatches.filter(listing => {
    const regionMatch = selectedRegion === 'all' || listing.region === selectedRegion;
    if (filter === 'all') return regionMatch;
    return regionMatch && listing.matches.some(match => match.status === filter);
  });

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'pending': return 'bg-yellow-100 text-yellow-800';
      case 'approved': return 'bg-green-100 text-green-800';
      case 'rejected': return 'bg-red-100 text-red-800';
      case 'flagged': return 'bg-orange-100 text-orange-800';
      default: return 'bg-gray-100 text-gray-800';
    }
  };

  const getPriorityColor = (priority: string) => {
    switch (priority) {
      case 'high': return 'bg-red-100 text-red-800';
      case 'medium': return 'bg-yellow-100 text-yellow-800';
      case 'low': return 'bg-green-100 text-green-800';
      default: return 'bg-gray-100 text-gray-800';
    }
  };

  const getStatusIcon = (status: string) => {
    switch (status) {
      case 'approved': return <CheckCircle className="h-4 w-4" />;
      case 'rejected': return <XCircle className="h-4 w-4" />;
      case 'flagged': return <AlertTriangle className="h-4 w-4" />;
      default: return <Clock className="h-4 w-4" />;
    }
  };

  const handleGlobalAction = (listingId: number, matchId: number, action: string) => {
    console.log(`Admin ${action} for listing ${listingId}, match ${matchId}`);
    // Handle global admin actions
  };

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900">Global Matching System</h1>
          <p className="text-gray-600 mt-1">Monitor and manage all food matches across the platform</p>
        </div>

        {/* Stats Overview */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div className="bg-white p-6 rounded-lg shadow-lg">
            <div className="flex items-center">
              <Clock className="h-8 w-8 text-yellow-600 mr-3" />
              <div>
                <p className="text-sm text-gray-600">Pending Matches</p>
                <p className="text-2xl font-bold text-yellow-600">
                  {globalMatches.reduce((acc, listing) => 
                    acc + listing.matches.filter(m => m.status === 'pending').length, 0
                  )}
                </p>
              </div>
            </div>
          </div>
          <div className="bg-white p-6 rounded-lg shadow-lg">
            <div className="flex items-center">
              <CheckCircle className="h-8 w-8 text-green-600 mr-3" />
              <div>
                <p className="text-sm text-gray-600">Approved Today</p>
                <p className="text-2xl font-bold text-green-600">
                  {globalMatches.reduce((acc, listing) => 
                    acc + listing.matches.filter(m => m.status === 'approved').length, 0
                  )}
                </p>
              </div>
            </div>
          </div>
          <div className="bg-white p-6 rounded-lg shadow-lg">
            <div className="flex items-center">
              <AlertTriangle className="h-8 w-8 text-orange-600 mr-3" />
              <div>
                <p className="text-sm text-gray-600">Flagged Issues</p>
                <p className="text-2xl font-bold text-orange-600">
                  {globalMatches.reduce((acc, listing) => 
                    acc + listing.matches.filter(m => m.status === 'flagged').length, 0
                  )}
                </p>
              </div>
            </div>
          </div>
          <div className="bg-white p-6 rounded-lg shadow-lg">
            <div className="flex items-center">
              <MapPin className="h-8 w-8 text-blue-600 mr-3" />
              <div>
                <p className="text-sm text-gray-600">Active Regions</p>
                <p className="text-2xl font-bold text-blue-600">{regions.length - 1}</p>
              </div>
            </div>
          </div>
        </div>

        {/* Filters */}
        <div className="bg-white rounded-lg shadow-lg p-6 mb-8">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
              <select
                value={filter}
                onChange={(e) => setFilter(e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="all">All Matches</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="flagged">Flagged</option>
                <option value="rejected">Rejected</option>
              </select>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">Filter by Region</label>
              <select
                value={selectedRegion}
                onChange={(e) => setSelectedRegion(e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              >
                {regions.map(region => (
                  <option key={region} value={region}>
                    {region === 'all' ? 'All Regions' : region}
                  </option>
                ))}
              </select>
            </div>

            <div className="flex items-end">
              <button className="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors flex items-center justify-center">
                <Filter className="h-4 w-4 mr-2" />
                Apply Filters
              </button>
            </div>
          </div>
        </div>

        {/* Matches List */}
        <div className="space-y-6">
          {filteredMatches.map((listing) => (
            <div key={listing.id} className="bg-white rounded-lg shadow-lg p-6">
              <div className="flex justify-between items-start mb-6">
                <div>
                  <h3 className="text-xl font-semibold text-gray-900">{listing.foodName}</h3>
                  <p className="text-gray-600">
                    {listing.restaurant} • {listing.location} ({listing.region} Region)
                  </p>
                  <div className="flex items-center mt-2 text-sm text-gray-500">
                    <Users className="h-4 w-4 mr-1" />
                    {listing.quantity} • Expires in {listing.expiryTime}
                  </div>
                </div>
                <span className="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                  {listing.matches.length} matches
                </span>
              </div>

              <div className="space-y-4">
                {listing.matches.map((match) => (
                  <div key={match.id} className="border border-gray-200 rounded-lg p-4">
                    <div className="flex justify-between items-start mb-3">
                      <div>
                        <h4 className="font-semibold text-gray-900">{match.organizationName}</h4>
                        <p className="text-sm text-gray-600">Contact: {match.contactPerson}</p>
                      </div>
                      <div className="flex space-x-2">
                        <span className={`px-2 py-1 rounded-full text-xs font-medium ${getPriorityColor(match.priority)}`}>
                          {match.priority} priority
                        </span>
                        <span className={`px-2 py-1 rounded-full text-xs font-medium flex items-center ${getStatusColor(match.status)}`}>
                          {getStatusIcon(match.status)}
                          <span className="ml-1 capitalize">{match.status}</span>
                        </span>
                      </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                      <div className="space-y-1 text-sm text-gray-600">
                        <div className="flex items-center">
                          <MapPin className="h-4 w-4 mr-2" />
                          {match.distance} away
                        </div>
                        <div className="flex items-center">
                          <Users className="h-4 w-4 mr-2" />
                          Capacity: {match.capacity}
                        </div>
                      </div>

                      <div className="space-y-1 text-sm text-gray-600">
                        <div className="flex items-center">
                          <Phone className="h-4 w-4 mr-2" />
                          {match.phone}
                        </div>
                        <div className="flex items-center">
                          <Mail className="h-4 w-4 mr-2" />
                          {match.email}
                        </div>
                      </div>

                      <div>
                        <p className="text-sm font-medium text-gray-700">Rating</p>
                        <div className="flex items-center">
                          <div className="flex text-yellow-400">
                            {'★'.repeat(Math.floor(match.rating))}
                            {'☆'.repeat(5 - Math.floor(match.rating))}
                          </div>
                          <span className="ml-2 text-sm text-gray-600">{match.rating}</span>
                        </div>
                      </div>
                    </div>

                    {/* Admin Actions */}
                    <div className="flex space-x-2 pt-3 border-t border-gray-200">
                      {match.status === 'pending' && (
                        <>
                          <button
                            onClick={() => handleGlobalAction(listing.id, match.id, 'approve')}
                            className="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition-colors flex items-center"
                          >
                            <CheckCircle className="h-3 w-3 mr-1" />
                            Approve
                          </button>
                          <button
                            onClick={() => handleGlobalAction(listing.id, match.id, 'reject')}
                            className="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition-colors flex items-center"
                          >
                            <XCircle className="h-3 w-3 mr-1" />
                            Reject
                          </button>
                        </>
                      )}
                      {match.status === 'flagged' && (
                        <button
                          onClick={() => handleGlobalAction(listing.id, match.id, 'investigate')}
                          className="bg-orange-600 text-white px-3 py-1 rounded text-sm hover:bg-orange-700 transition-colors flex items-center"
                        >
                          <AlertTriangle className="h-3 w-3 mr-1" />
                          Investigate
                        </button>
                      )}
                      <button
                        onClick={() => handleGlobalAction(listing.id, match.id, 'view_details')}
                        className="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors"
                      >
                        View Details
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          ))}
        </div>

        {filteredMatches.length === 0 && (
          <div className="text-center py-12">
            <MapPin className="mx-auto h-12 w-12 text-gray-400 mb-4" />
            <h3 className="text-lg font-medium text-gray-900">No matches found</h3>
            <p className="text-gray-500">Try adjusting your filters to see more results.</p>
          </div>
        )}
      </div>
    </div>
  );
};

export default AdminMatching;