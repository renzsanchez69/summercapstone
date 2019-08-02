import React, 
	{Component} 
	from 'react';
import {Platform, 
	StyleSheet, 
	Text, 
	View, 
	AsyncStorage,
	Image,
	NetInfo,
	TouchableWithoutFeedback,
	TextInput,
	Picker,
	CheckBox} 
	from 'react-native';
import {
	Container, 
	Icon,
	Spinner} 
	from 'native-base';
import SyncStorage   from 'sync-storage';
import Geolocation   from 'react-native-geolocation-service';


/* -- Custom Components  -- */
import Constants      from './Constants.js';
import UserHomePage   from '../user/UserHomePage.js';
import OwnersHomePage from '../owner/OwnersHomePage.js';

export default class HomeDashboard extends Component{

	state = {
		loggedInformation : {
			role: Constants.ROLES.DEFAULT_INFO
		}
	}

	componentDidMount(){
		this.setState({loggedInformation:this.props.doGetLoggedInformation});
	}

	

	homeDashboardDisplay = ()=>{
		switch(this.state.loggedInformation.role){
			case Constants.ROLES.USER_ONLY:
				return 	<UserHomePage
							doSetLoggedInformation = {this.props.doSetLoggedInformation}
							doUseFirebaseObject    = {this.props.doUseFirebaseObject}
							doChangeUserPassword   = {this.props.doChangeUserPassword}
							doSignOutAccount       = {this.props.doSignOutAccount}
							doGetUsersLocation     = {this.props.doGetUsersLocation}
							doSendAReportMessage   = {this.props.doSendAReportMessage}
							doGetLoggedInformation = {this.props.doGetLoggedInformation}
							doUpdateUserInfo       = {this.props.doUpdateUserInfo} />;
			case Constants.ROLES.RESTAURANT_OWNER:
				return 	<OwnersHomePage
							doChangeUserPassword   = {this.props.doChangeUserPassword}
							doSignOutAccount       = {this.props.doSignOutAccount}
							doGetUsersLocation     = {this.props.doGetUsersLocation}
							doSendAReportMessage   = {this.props.doSendAReportMessage}
							doSetLoggedInformation = {this.props.doSetLoggedInformation}
							doGetLoggedInformation = {this.props.doGetLoggedInformation}
							doSetRestaurantAddress = {this.props.doSetRestaurantAddress}
							doAddANewDish          = {this.props.doAddANewDish}
							doUseFirebaseObject    = {this.props.doUseFirebaseObject}
							doDeleteADishMenu      = {this.props.doDeleteADishMenu}
							doUpdateUserInfo       = {this.props.doUpdateUserInfo} />;
		}

	}

	render() {
	    return (
	    	<React.Fragment>
	    		{this.homeDashboardDisplay()}
	    	</React.Fragment>
		);
  	}
}
