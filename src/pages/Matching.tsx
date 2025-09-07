import React, { useState } from 'react';
import { MapPin, Users, Clock, CheckCircle, XCircle, Phone, Mail, Filter } from 'lucide-react';

const Matching: React.FC = () => {
  const [selectedListing, setSelectedListing] = useState<number | null>(null);
  const [filter, setFilter] = useState('all');

  const listings = [
    {
      id: 1,
      name: 'Fresh Sandwiches',
      quantity: '25 pieces',
      location: 'Downtown Branch',
      address: '123 Main St, Downtown',
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
          dietaryRequirements: ['Vegetarian options available'],
          rating: 4.8,
          status: 'pending'
        },
        {
          id: 2,
          organizationName: 'City Homeless Shelter',
          contactPerson: 'Mike Chen',
          phone: '+1 234-567-8901',
          email: 'mike@cityshelter.org',
          distance: '1.2 miles',
          capacity: '100 people',
          dietaryRequirements: ['Halal required'],
          rating: 4.6,
          status: 'pending'
        }
      ]
    },
    {
      id: 2,
      name: 'Pasta Salad',
      quantity: '3 containers',
      location: 'West Side Kitchen',
      address: '456 Oak Ave, West Side',
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
          dietaryRequirements: ['Gluten-free needed'],
          rating: 4.9,
          status: 'approved'
        }
      ]
    },
    {
      id: 3,
      name: 'Bread Rolls',
      quantity: '50 pieces',
      location: 'Central Bakery',
      address: '789 Pine St, Central',
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
          dietaryRequirements: ['Low sodium preferred'],
          rating: 4.7,
          status: 'rejected'
        },
        {
          id: 5,
          organizationName: 'Youth Program Center',
          contactPerson: 'Amanda Davis',
          phone: '+1 234-567-8904',
          email: 'amanda@youthcenter.org',
          distance: '1.5 miles',
          capacity: '40 youth',
          dietaryRequirements: ['No specific requirements'],
          rating: 4.5,
          status: 'pending'
        }
      ]
    }
  ];

  const filteredListings = listings.filter(listing => {
    if (filter === 'all') return true;
    return listing.matches.some(match => match.status === filter);
  });

  const handleMatchAction = (listingId: number, matchId: number, action: 'approve' | 'reject') => {
    console.log(`${action} match ${matchId} for listing ${listingId}`);
    // Here you would typically update the match status via API
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'pending': return 'bg-yellow-100 text-yellow-800';
      case 'approved': return 'bg-green-100 text-green-800';
      case 'rejected': return 'bg-red-100 text-red-800';
      default: return 'bg-gray-100 text-gray-800';
    }
  };

  const getStatusIcon = (status: string) => {
    switch (status) {
      case 'approved': return <CheckCircle className="h-4 w-4" />;
      case 'rejected': return <XCircle className="h-4 w-4" />;
      default: return <Clock className="h-4 w-4" />;
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900">Food Matching</h1>
          <p className="text-gray-600 mt-1">Review and manage matches between your listings and recipients</p>
        </div>

        {/* Filter */}
        <div className="bg-white rounded-lg shadow-lg p-6 mb-8">
          <div className="flex items-center space-x-4">
            <Filter className="h-5 w-5 text-gray-500" />
            <span className="text-sm font-medium text-gray-700">Filter by status:</span>
            <select
              value={filter}
              onChange={(e) => setFilter(e.target.value)}
              className="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
            >
              <option value="all">All Matches</option>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
          {/* Listings Column */}
          <div className="space-y-6">
            <h2 className="text-xl font-semibold text-gray-900">Your Listings</h2>
            {filteredListings.map((listing) => (
              <div
                key={listing.id}
                className={`bg-white rounded-lg shadow-lg p-6 cursor-pointer transition-all ${
                  selectedListing === listing.id ? 'ring-2 ring-green-500' : 'hover:shadow-xl'
                }`}
                onClick={() => setSelectedListing(listing.id)}
              >
                <div className="flex justify-between items-start mb-4">
                  <h3 className="text-lg font-semibold text-gray-900">{listing.name}</h3>
                  <span className="text-sm bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                    {listing.matches.length} matches
                  </span>
                </div>

                <div className="space-y-2 text-sm text-gray-600">
                  <div className="flex items-center">
                    <Users className="h-4 w-4 mr-2" />
                    {listing.quantity}
                  </div>
                  <div className="flex items-center">
                    <MapPin className="h-4 w-4 mr-2" />
                    {listing.location}
                  </div>
                  <div className="flex items-center">
                    <Clock className="h-4 w-4 mr-2" />
                    Expires in {listing.expiryTime}
                  </div>
                </div>

                <div className="mt-4 flex space-x-2">
                  {listing.matches.map((match) => (
                    <span
                      key={match.id}
                      className={`px-2 py-1 rounded-full text-xs font-medium flex items-center ${getStatusColor(match.status)}`}
                    >
                      {getStatusIcon(match.status)}
                      <span className="ml-1">{match.status}</span>
                    </span>
                  ))}
                </div>
              </div>
            ))}
          </div>

          {/* Matches Column */}
          <div className="space-y-6">
            <h2 className="text-xl font-semibold text-gray-900">Matched Recipients</h2>
            {selectedListing ? (
              <div className="space-y-4">
                {listings.find(l => l.id === selectedListing)?.matches.map((match) => (
                  <div key={match.id} className="bg-white rounded-lg shadow-lg p-6">
                    <div className="flex justify-between items-start mb-4">
                      <div>
                        <h3 className="text-lg font-semibold text-gray-900">{match.organizationName}</h3>
                        <p className="text-sm text-gray-600">Contact: {match.contactPerson}</p>
                      </div>
                      <span className={`px-2 py-1 rounded-full text-xs font-medium flex items-center ${getStatusColor(match.status)}`}>
                        {getStatusIcon(match.status)}
                        <span className="ml-1 capitalize">{match.status}</span>
                      </span>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                      <div className="space-y-2 text-sm text-gray-600">
                        <div className="flex items-center">
                          <MapPin className="h-4 w-4 mr-2" />
                          {match.distance} away
                        </div>
                        <div className="flex items-center">
                          <Users className="h-4 w-4 mr-2" />
                          Capacity: {match.capacity}
                        </div>
                        <div className="flex items-center">
                          <Phone className="h-4 w-4 mr-2" />
                          {match.phone}
                        </div>
                        <div className="flex items-center">
                          <Mail className="h-4 w-4 mr-2" />
                          {match.email}
                        </div>
                      </div>

                      <div className="space-y-2">
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
                        <div>
                          <p className="text-sm font-medium text-gray-700">Dietary Requirements</p>
                          <div className="space-y-1">
                            {match.dietaryRequirements.map((req, index) => (
                              <span key={index} className="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1">
                                {req}
                              </span>
                            ))}
                          </div>
                        </div>
                      </div>
                    </div>

                    {match.status === 'pending' && (
                      <div className="flex space-x-3 pt-4 border-t border-gray-200">
                        <button
                          onClick={() => handleMatchAction(selectedListing, match.id, 'approve')}
                          className="flex-1 bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors flex items-center justify-center"
                        >
                          <CheckCircle className="h-4 w-4 mr-2" />
                          Approve Match
                        </button>
                        <button
                          onClick={() => handleMatchAction(selectedListing, match.id, 'reject')}
                          className="flex-1 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors flex items-center justify-center"
                        >
                          <XCircle className="h-4 w-4 mr-2" />
                          Reject Match
                        </button>
                      </div>
                    )}

                    {match.status === 'approved' && (
                      <div className="pt-4 border-t border-gray-200">
                        <div className="bg-green-50 p-4 rounded-md">
                          <div className="flex items-center">
                            <CheckCircle className="h-5 w-5 text-green-400 mr-2" />
                            <span className="text-sm font-medium text-green-800">
                              Match approved! The recipient has been notified.
                            </span>
                          </div>
                          <p className="text-sm text-green-700 mt-1">
                            QR code has been generated for pickup verification.
                          </p>
                        </div>
                      </div>
                    )}
                  </div>
                )) || (
                  <div className="text-center py-8 text-gray-500">
                    <Users className="mx-auto h-12 w-12 text-gray-400 mb-4" />
                    <p>No matches found for this listing.</p>
                  </div>
                )}
              </div>
            ) : (
              <div className="text-center py-12 text-gray-500">
                <MapPin className="mx-auto h-12 w-12 text-gray-400 mb-4" />
                <p>Select a listing to view its matches</p>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default Matching;