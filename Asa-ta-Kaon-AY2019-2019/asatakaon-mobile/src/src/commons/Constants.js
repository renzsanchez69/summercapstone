const Constants = {
	ROLES                    : {
		RESTAURANT_OWNER     : 'RESTAURANT_OWNER',
		USER_ONLY            : 'USER_ONLY',
		DEFAULT_INFO         : 'DEFAULT_INFO'
	},
	NOTIFICATION_STATUS      : {
		MARK_READ            : 'MARK_READ',
		UNREAD               : 'UNREAD'
	},
 	BOOKING_STATUS           : {
 		PENDING              : 'PENDING',
 		BOOKED               : 'BOOKED',
 		DENIED               : 'DENIED',
 		CLAIMED              : 'CLAIMED',
 		NOT_CLAIMED          : 'NOT_CLAIMED'
 	},
	USER_ROLE_PAGES          : {
		LANDING_PAGE         : 'LANDING_PAGE',
		BOOKINGS             : 'BOOKINGS',
		REQUESTS             : 'REQUESTS',
		RESTAURANT_DETAILS   : 'RESTAURANT_DETAILS'
	},

	OWNER_ROLE_PAGES         : {
		LANDING_PAGE         : 'LANDING_PAGE',
		RESTAURANT_INFO      : 'RESTAURANT_INFO',
		BOOKED_USERS         : 'BOOKED_USERS',
		RESTAURANT_LOCATION  : 'RESTAURANT_LOCATION',
		ADD_FOOD_MENU        : 'ADD_FOOD_MENU',
		VIEW_FOOD_MENU       : 'VIEW_FOOD_MENU',
		EDIT_PRICE_RANGE     : 'EDIT_PRICE_RANGE'
	},

	RESTAURANT_PLACE_STATUS  : {
		BLOCKED              : 'BLOCKED',
		ACCEPTED             : 'ACCEPTED'
	},

	ACCOUNT_USER_STATUS      : {
		BLOCKED              : 'BLOCKED',
		ACCEPTED             : 'ACCEPTED'
	},

	COMMON_ROLE_PAGES        : {
		USER_INFO            : 'USER_INFO',
		CHANGE_PASSWORD      : 'CHANGE_PASSWORD',
		ACCOUNT_DETAILS      : 'ACCOUNT_DETAILS',
		SEND_A_REPORT        : 'SEND_A_REPORT'
	},

	LOCAL_ACCOUNT_KEY        : 'LOCAL_ACCOUNT_KEY',
	APP_PAGES                : {
		WELCOME_APP_PAGE     : 'WELCOME_APP_PAGE',
		LOADING_SCREEN_APP   : 'LOADING_SCREEN_APP',
		SPLASH_SCREEN_APP    : 'SPLASH_SCREEN_APP',
		FIND_RESTAURANT_APP  : 'FIND_RESTAURANT_APP',
		LOGIN_APP_PAGE       : 'LOGIN_APP_PAGE',
		SIGN_APP_PAGE        : 'SIGN_APP_PAGE',
		SIGN_RESTAURANT      : 'SIGN_RESTAURANT',
		HOME_DASHBOARD       : 'HOME_DASHBOARD'
	},

	LOADING_TEXT             : {
		LOGGING_IN           : 'Logging you in, getting everything ready..',
		LOGGING_OUT          : 'Signing out your account, please wait..',
		GETTING_CONNECTED    : 'Connecting to the server, please wait..',
		USER_OFFLINE         : 'Check your connectivity, you are offline.',
		USER_CONNECTED       : 'You are now connected'
	},

	TIME                     : {
		SPLASH_SCREEN_TIME   : 2500
	},
	CREDENTIALS_POLICY       : {
		MIN_USERNAME         : 8,
		MAX_USERNAME         : 25,
		MIN_PASSWORD         : 8,
		MAX_PASSWORD         : 20,
		MAX_RESTAURANT_NAME  : 30,
		MAX_FOOD_MENU        : 25,
		MAX_DISH_DESCRIPTION : 20,
		MAX_REPORT_SUBJECT   : 30,
		MAX_REPORT_CONTENT   : 55
	},
	REPORT_DISPLAY_TIME     : 3000,
	FIRE_BASE_CONFIG : {
		apiKey: "AIzaSyCmZpd23BMyDDTnNhcHoXPUnD6eLgxZ6J8",
	    authDomain: "asatakaonproj.firebaseapp.com",
	    databaseURL: "https://asatakaonproj.firebaseio.com",
	    projectId: "asatakaonproj",
	    storageBucket: "asatakaonproj.appspot.com",
	    messagingSenderId: "1094462141971"
	},
	SELECT_PHOTO_OPTIONS        : {
  		title                   : 'Select Avatar',
  		storageOptions          : {
    		skipBackup          : true,
    		path                : 'images'
  		},
  		quality                 : 0.7,
  		maxWidth                : 560, 
  		maxHeight               : 500
	},
	LOCAL_IMAGE_FILE_SIZE_LIMIT : 10000000
}; 
export default Constants;

