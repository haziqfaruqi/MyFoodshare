import React from 'react';
import { Link } from 'react-router-dom';
import { Home, ArrowLeft, Search } from 'lucide-react';

const NotFound: React.FC = () => {
  return (
    <div className="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-md w-full text-center">
        <div className="mb-8">
          <div className="text-6xl font-bold text-green-600 mb-4">404</div>
          <h1 className="text-3xl font-bold text-gray-900 mb-4">Page Not Found</h1>
          <p className="text-gray-600 mb-8">
            Sorry, we couldn't find the page you're looking for. 
            It might have been moved, deleted, or you entered the wrong URL.
          </p>
        </div>

        <div className="space-y-4">
          <Link
            to="/"
            className="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center justify-center"
          >
            <Home className="h-4 w-4 mr-2" />
            Go to Homepage
          </Link>
          
          <button
            onClick={() => window.history.back()}
            className="w-full border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors flex items-center justify-center"
          >
            <ArrowLeft className="h-4 w-4 mr-2" />
            Go Back
          </button>
        </div>

        <div className="mt-12 p-6 bg-white rounded-lg shadow-lg">
          <h2 className="text-lg font-semibold text-gray-900 mb-4">What you can do:</h2>
          <ul className="text-left space-y-2 text-gray-600">
            <li className="flex items-center">
              <Search className="h-4 w-4 mr-2 text-green-600" />
              Check the URL for typos
            </li>
            <li className="flex items-center">
              <Search className="h-4 w-4 mr-2 text-green-600" />
              Use the navigation menu
            </li>
            <li className="flex items-center">
              <Search className="h-4 w-4 mr-2 text-green-600" />
              Contact support if you think this is an error
            </li>
          </ul>
        </div>

        <div className="mt-8">
          <p className="text-sm text-gray-500">
            Need help? <Link to="/contact" className="text-green-600 hover:text-green-700 font-medium">Contact our support team</Link>
          </p>
        </div>
      </div>
    </div>
  );
};

export default NotFound;