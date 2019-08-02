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

/* -- Custom Components  -- */
import Constants         from '../commons/Constants.js';
import ChangePassword    from '../commons/ChangePassword.js';
import AccountDetails    from '../commons/AccountDetails.js';
import UserInfo          from '../commons/UserInfo.js';
import SendAReport       from '../commons/SendAReport.js';
import OwnersLandingPage from './OwnersLandingPage.js';
import OwnersRestaurant  from './OwnersRestaurant.js';
import OwnersLocation    from './OwnersLocation.js';
import AddFoodMenu       from './AddFoodMenu.js';
import FoodMenu          from './FoodMenu.js';
import Booked            from './Booked.js';
import PriceRange        from './PriceRange.js';

export default class OwnersHomePage extends Component{

	state = {
		ownerRoleOperation : Constants.OWNER_ROLE_PAGES.LANDING_PAGE
	}

	setHomePage = (content)=>{
		this.props.doSendAReportMessage('');
		this.setState({ownerRoleOperation:content});
	}

	componentDidMount(){
	}

	OwnerHomePageDisplay = ()=>{
		switch(this.state.ownerRoleOperation){
			case Constants.OWNER_ROLE_PAGES.LANDING_PAGE:
				return 	<OwnersLandingPage
							doSetLoggedInformation = {this.props.doSetLoggedInformation}
							doUseFirebaseObject    = {this.props.doUseFirebaseObject}
							doGetLoggedInformation = {this.props.doGetLoggedInformation}
							doSendAReportMessage   = {this.props.doSendAReportMessage}
							doSetHomePage          = {this.setHomePage} />;
			case Constants.COMMON_ROLE_PAGES.USER_INFO:
				return 	<UserInfo
							doChangeUserPassword   = {this.props.doChangeUserPassword}
							doSetHomePage          = {this.setHomePage}
							doSignOutAccount       = {this.props.doSignOutAccount}
							doSendAReportMessage   = {this.props.doSendAReportMessage} />;
			case Constants.COMMON_ROLE_PAGES.CHANGE_PASSWORD:
				return 	<ChangePassword
							doChangeUserPassword   = {this.props.doChangeUserPassword}
							doSendAReportMessage   = {this.props.doSendAReportMessage}
							doGetLoggedInformation = {this.props.doGetLoggedInformation}
							doSetHomePage          = {this.setHomePage} />;
			case Constants.COMMON_ROLE_PAGES.ACCOUNT_DETAILS:
				return  <AccountDetails
							doSetHomePage          = {this.setHomePage}
							doUpdateUserInfo       = {this.props.doUpdateUserInfo}
							doGetLoggedInformation = {this.props.doGetLoggedInformation} />;
			case Constants.OWNER_ROLE_PAGES.RESTAURANT_INFO:
				return 	<OwnersRestaurant
							doUseFirebaseObject    = {this.props.doUseFirebaseObject}
							doSendAReportMessage   = {this.props.doSendAReportMessage}
							doSetHomePage          = {this.setHomePage}
							doSetLoggedInformation = {this.props.doSetLoggedInformation}
							doGetLoggedInformation = {this.props.doGetLoggedInformation} />;
			case Constants.OWNER_ROLE_PAGES.RESTAURANT_LOCATION:
				return 	<OwnersLocation 
							doSetRestaurantAddress = {this.props.doSetRestaurantAddress}
							doSendAReportMessage   = {this.props.doSendAReportMessage}
							doGetUsersLocation     = {this.props.doGetUsersLocation}
							doSetHomePage          = {this.setHomePage} />;
			case Constants.OWNER_ROLE_PAGES.ADD_FOOD_MENU:
				return 	<AddFoodMenu 
							doAddANewDish          = {this.props.doAddANewDish}
							doSendAReportMessage   = {this.props.doSendAReportMessage}
							doSetHomePage          = {this.setHomePage} />;
			case Constants.OWNER_ROLE_PAGES.VIEW_FOOD_MENU:
				return 	<FoodMenu
							doDeleteADishMenu      = {this.props.doDeleteADishMenu}
							doUseFirebaseObject    = {this.props.doUseFirebaseObject}
							doGetLoggedInformation = {this.props.doGetLoggedInformation}
							doSetHomePage          = {this.setHomePage} />;
			case Constants.OWNER_ROLE_PAGES.BOOKED_USERS:
				return 	<Booked
							doSendAReportMessage   = {this.props.doSendAReportMessage}
							doUseFirebaseObject    = {this.props.doUseFirebaseObject}
							doGetLoggedInformation = {this.props.doGetLoggedInformation}
							doSetHomePage          = {this.setHomePage} />;
			case Constants.COMMON_ROLE_PAGES.SEND_A_REPORT:
				return 	<SendAReport
							doSendAReportMessage   = {this.props.doSendAReportMessage}
							doUseFirebaseObject    = {this.props.doUseFirebaseObject}
							doGetLoggedInformation = {this.props.doGetLoggedInformation}
							doSetHomePage          = {this.setHomePage}/>;
			case Constants.OWNER_ROLE_PAGES.EDIT_PRICE_RANGE:
				return 	<PriceRange
							doSendAReportMessage   = {this.props.doSendAReportMessage}
							doUseFirebaseObject    = {this.props.doUseFirebaseObject}
							doGetLoggedInformation = {this.props.doGetLoggedInformation}
							doSetHomePage          = {this.setHomePage}/>;
		}
	}

	OwnerHomePageTabs = ()=>{
		if(this.state.ownerRoleOperation == Constants.COMMON_ROLE_PAGES.USER_INFO
			|| this.state.ownerRoleOperation == Constants.OWNER_ROLE_PAGES.LANDING_PAGE
			|| this.state.ownerRoleOperation == Constants.OWNER_ROLE_PAGES.RESTAURANT_INFO
			|| this.state.ownerRoleOperation == Constants.OWNER_ROLE_PAGES.BOOKED_USERS ){
			return 	<View style={{
							height: '13.5%',
							width:'100%',
							position: 'absolute',
							top: '86.5%',
							flexDirection: 'row',
							justifyContent: 'space-evenly'
					}}>	
						<View style={{
								height:65,
								width: 65,
								position:'relative',
								borderRadius: 90,
							    borderColor: '#ddd',
							    borderBottomWidth: 0,
							    shadowColor: '#000',
							    shadowOffset: {
									width: 0,
									height: 5,
								},
								shadowOpacity: 0.34,
								shadowRadius: 6.27,
								elevation: 10,
							     backgroundColor: '#fff'
						}}>
							<TouchableWithoutFeedback
								onPress={()=>this.setHomePage(Constants.OWNER_ROLE_PAGES.LANDING_PAGE)}>
								<Text style={{
										height:'100%',
										width:'100%',
										position:'relative',
										textAlign:'center',
										textAlignVertical:'center',
										color: '#000',
										fontWeight:'bold',
										fontSize: 11
								}}>
									<Icon
										style={{fontSize:35}}
										name = 'md-map'
										type = 'Ionicons'/>{'\nMap'}
								</Text>
							</TouchableWithoutFeedback>
						</View>


						<View style={{
								height:65,
								width: 65,
								position:'relative',
								borderRadius: 90,
							    borderColor: '#ddd',
							    borderBottomWidth: 0,
							    shadowColor: '#000',
							    shadowOffset: {
									width: 0,
									height: 5,
								},
								shadowOpacity: 0.34,
								shadowRadius: 6.27,
								elevation: 10,
							     backgroundColor: '#fff'
						}}>
							<TouchableWithoutFeedback
								onPress={()=>this.setHomePage(Constants.OWNER_ROLE_PAGES.RESTAURANT_INFO)}> 
								<Text style={{
										height:'100%',
										width:'100%',
										position:'relative',
										textAlign:'center',
										textAlignVertical:'center',
										color: '#000',
										fontWeight:'bold',
										fontSize: 9
								}}>
									<Icon
										style={{fontSize:35}}
										name = 'ios-restaurant'
										type = 'Ionicons'/>{'\nRestaurant'}
								</Text>
							</TouchableWithoutFeedback>
						</View>

						<View style={{
								height:65,
								width: 65,
								position:'relative',
								borderRadius: 90,
							    borderColor: '#ddd',
							    borderBottomWidth: 0,
							    shadowColor: '#000',
							    shadowOffset: {
									width: 0,
									height: 5,
								},
								shadowOpacity: 0.34,
								shadowRadius: 6.27,
								elevation: 10,
							    backgroundColor: '#fff'
						}}>
							<TouchableWithoutFeedback
								onPress={()=>this.setHomePage(Constants.OWNER_ROLE_PAGES.BOOKED_USERS)}> 
								<Text style={{
										height:'100%',
										width:'100%',
										position:'relative',
										textAlign:'center',
										textAlignVertical:'center',
										color: '#000',
										fontWeight:'bold',
										fontSize: 10
								}}>
									<Icon
										style={{fontSize:35}}
										name = 'bookmarks'
										type = 'Entypo'/>{'\nBooking'}
								</Text>
							</TouchableWithoutFeedback>
						</View>
						<View style={{
								height:65,
								width: 65,
								position:'relative',
								borderRadius: 90,
							    borderColor: '#ddd',
							    borderBottomWidth: 0,
							    shadowColor: '#000',
							    shadowOffset: {
									width: 0,
									height: 5,
								},
								shadowOpacity: 0.34,
								shadowRadius: 6.27,
								elevation: 11,
							     backgroundColor: '#fff'
						}}>
							<TouchableWithoutFeedback
								onPress={()=>this.setHomePage(Constants.COMMON_ROLE_PAGES.USER_INFO)}>
								<Text style={{
										height:'100%',
										width:'100%',
										position:'relative',
										textAlign:'center',
										textAlignVertical:'center',
										color: '#000',
										fontWeight:'bold',
										fontSize: 10
								}}>
									<Icon
										style={{fontSize:35}}
										name = 'infocirlce'
										type = 'AntDesign'/>{'\nUser-info'}
								</Text>
							</TouchableWithoutFeedback>
						</View>
					</View>
		}
		else return;
	}

	render() {
	    return (
	    	<React.Fragment>
	    		{this.OwnerHomePageDisplay()}
	    		{this.OwnerHomePageTabs()}
	    	</React.Fragment>
		);
  	}
}
