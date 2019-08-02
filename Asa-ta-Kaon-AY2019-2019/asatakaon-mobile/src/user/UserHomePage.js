import React, { Component } from "react";
import {
  Platform,
  StyleSheet,
  Text,
  View,
  AsyncStorage,
  Image,
  NetInfo,
  TouchableWithoutFeedback,
  TextInput,
  Picker,
  CheckBox
} from "react-native";
import { Container, Icon, Spinner } from "native-base";
import Geolocation from "react-native-geolocation-service";

/* -- Custom Components  -- */
import Constants from "../commons/Constants.js";
import UserInfo from "../commons/UserInfo.js";
import ChangePassword from "../commons/ChangePassword.js";
import AccountDetails from "../commons/AccountDetails.js";
import SendAReport from "../commons/SendAReport.js";
import BookingsPage from "./BookingsPage.js";
import LandingPage from "./LandingPage.js";
import RestaurantDetailsPage from "./RestaurantDetailsPage.js";
import RequestsPage from "./RequestsPage.js";

export default class UserHomePage extends Component {
  state = {
    userRoleOperation: Constants.USER_ROLE_PAGES.LANDING_PAGE,
    pressedRestaurantDetails: {}
  };

  setHomePage = content => {
    this.props.doSendAReportMessage("");
    this.setState({ userRoleOperation: content });
  };

  setPressedRestaurantDetails = restaurant => {
    this.setState({ pressedRestaurantDetails: restaurant });
  };

  UserHomePageDisplay = () => {
    switch (this.state.userRoleOperation) {
      case Constants.USER_ROLE_PAGES.REQUESTS:
        return (
          <RequestsPage
            doSendAReportMessage={this.props.doSendAReportMessage}
            doSetLoggedInformation={this.props.doSetLoggedInformation}
            doUseFirebaseObject={this.props.doUseFirebaseObject}
            doGetLoggedInformation={this.props.doGetLoggedInformation}
          />
        );
      case Constants.USER_ROLE_PAGES.LANDING_PAGE:
        return (
          <LandingPage
            doSetLoggedInformation={this.props.doSetLoggedInformation}
            doSetRestaurantDetails={this.setPressedRestaurantDetails}
            doUseFirebaseObject={this.props.doUseFirebaseObject}
            doSetHomePage={this.setHomePage}
            doSendAReportMessage={this.props.doSendAReportMessage}
            doGetLoggedInformation={this.props.doGetLoggedInformation}
            doGetUsersLocation={this.props.doGetUsersLocation}
          />
        );
      case Constants.USER_ROLE_PAGES.BOOKINGS:
        return (
          <BookingsPage
            doSendAReportMessage={this.props.doSendAReportMessage}
            doUseFirebaseObject={this.props.doUseFirebaseObject}
            doGetLoggedInformation={this.props.doGetLoggedInformation}
          />
        );
      case Constants.COMMON_ROLE_PAGES.USER_INFO:
        return (
          <UserInfo
            doChangeUserPassword={this.props.doChangeUserPassword}
            doSetHomePage={this.setHomePage}
            doSignOutAccount={this.props.doSignOutAccount}
            doSendAReportMessage={this.props.doSendAReportMessage}
          />
        );
      case Constants.COMMON_ROLE_PAGES.CHANGE_PASSWORD:
        return (
          <ChangePassword
            doChangeUserPassword={this.props.doChangeUserPassword}
            doSendAReportMessage={this.props.doSendAReportMessage}
            doGetLoggedInformation={this.props.doGetLoggedInformation}
            doSetHomePage={this.setHomePage}
          />
        );
      case Constants.COMMON_ROLE_PAGES.ACCOUNT_DETAILS:
        return (
          <AccountDetails
            doSetHomePage={this.setHomePage}
            doUpdateUserInfo={this.props.doUpdateUserInfo}
            doSendAReportMessage={this.props.doSendAReportMessage}
            doGetLoggedInformation={this.props.doGetLoggedInformation}
          />
        );
      case Constants.USER_ROLE_PAGES.RESTAURANT_DETAILS:
        return (
          <RestaurantDetailsPage
            doSetLoggedInformation={this.props.doSetLoggedInformation}
            doUseFirebaseObject={this.props.doUseFirebaseObject}
            doGetLoggedInformation={this.props.doGetLoggedInformation}
            doSendAReportMessage={this.props.doSendAReportMessage}
            doGetRestaurantDetails={this.state.pressedRestaurantDetails}
            doSetHomePage={this.setHomePage}
          />
        );
      case Constants.COMMON_ROLE_PAGES.SEND_A_REPORT:
        return (
          <SendAReport
            doSendAReportMessage={this.props.doSendAReportMessage}
            doUseFirebaseObject={this.props.doUseFirebaseObject}
            doGetLoggedInformation={this.props.doGetLoggedInformation}
            doSetHomePage={this.setHomePage}
          />
        );
    }
  };

  UserHomePageTabs = () => {
    if (
      this.state.userRoleOperation == Constants.COMMON_ROLE_PAGES.USER_INFO ||
      this.state.userRoleOperation == Constants.USER_ROLE_PAGES.LANDING_PAGE ||
      this.state.userRoleOperation == Constants.USER_ROLE_PAGES.BOOKINGS ||
      this.state.userRoleOperation == Constants.USER_ROLE_PAGES.REQUESTS
    ) {
      return (
        <View
          style={{
            height: "13.5%",
            width: "100%",
            position: "absolute",
            top: "86.5%",
            flexDirection: "row",
            justifyContent: "space-evenly"
          }}
        >
          <View
            style={{
              height: 65,
              width: 65,
              position: "relative",
              borderRadius: 90,
              borderColor: "#ddd",
              borderBottomWidth: 0,
              shadowColor: "#000",
              shadowOffset: {
                width: 0,
                height: 5
              },
              shadowOpacity: 0.34,
              shadowRadius: 6.27,
              elevation: 10,
              backgroundColor: "#fff"
            }}
          >
            <TouchableWithoutFeedback
              onPress={() =>
                this.setHomePage(Constants.USER_ROLE_PAGES.LANDING_PAGE)
              }
            >
              <Text
                style={{
                  height: "100%",
                  width: "100%",
                  position: "relative",
                  textAlign: "center",
                  textAlignVertical: "center",
                  color: "#000",
                  fontWeight: "bold",
                  fontSize: 10
                }}
              >
                <Icon style={{ fontSize: 35 }} name="md-map" type="Ionicons" />
                {"\nMap"}
              </Text>
            </TouchableWithoutFeedback>
          </View>

          <View
            style={{
              height: 65,
              width: 65,
              position: "relative",
              borderRadius: 90,
              borderColor: "#ddd",
              borderBottomWidth: 0,
              shadowColor: "#000",
              shadowOffset: {
                width: 0,
                height: 5
              },
              shadowOpacity: 0.34,
              shadowRadius: 6.27,
              elevation: 10,
              backgroundColor: "#fff"
            }}
          >
            <TouchableWithoutFeedback
              onPress={() =>
                this.setHomePage(Constants.USER_ROLE_PAGES.REQUESTS)
              }
            >
              <Text
                style={{
                  height: "100%",
                  width: "100%",
                  position: "relative",
                  textAlign: "center",
                  textAlignVertical: "center",
                  color: "#000",
                  fontWeight: "bold",
                  fontSize: 10
                }}
              >
                <Icon
                  style={{ fontSize: 35 }}
                  name="add-to-list"
                  type="Entypo"
                />
                {"\nRequests"}
              </Text>
            </TouchableWithoutFeedback>
          </View>

          <View
            style={{
              height: 65,
              width: 65,
              position: "relative",
              borderRadius: 90,
              borderColor: "#ddd",
              borderBottomWidth: 0,
              shadowColor: "#000",
              shadowOffset: {
                width: 0,
                height: 5
              },
              shadowOpacity: 0.34,
              shadowRadius: 6.27,
              elevation: 10,
              backgroundColor: "#fff"
            }}
          >
            <TouchableWithoutFeedback
              onPress={() =>
                this.setHomePage(Constants.USER_ROLE_PAGES.BOOKINGS)
              }
            >
              <Text
                style={{
                  height: "100%",
                  width: "100%",
                  position: "relative",
                  textAlign: "center",
                  textAlignVertical: "center",
                  color: "#000",
                  fontWeight: "bold",
                  fontSize: 10
                }}
              >
                <Icon style={{ fontSize: 35 }} name="bookmarks" type="Entypo" />
                {"\nBooked"}
              </Text>
            </TouchableWithoutFeedback>
          </View>

          <View
            style={{
              height: 65,
              width: 65,
              position: "relative",
              borderRadius: 90,
              borderColor: "#ddd",
              borderBottomWidth: 0,
              shadowColor: "#000",
              shadowOffset: {
                width: 0,
                height: 5
              },
              shadowOpacity: 0.34,
              shadowRadius: 6.27,
              elevation: 10,
              backgroundColor: "#fff"
            }}
          >
            <TouchableWithoutFeedback
              onPress={() =>
                this.setHomePage(Constants.COMMON_ROLE_PAGES.USER_INFO)
              }
            >
              <Text
                style={{
                  height: "100%",
                  width: "100%",
                  position: "relative",
                  textAlign: "center",
                  textAlignVertical: "center",
                  color: "#000",
                  fontWeight: "bold",
                  fontSize: 10
                }}
              >
                <Icon
                  style={{ fontSize: 35 }}
                  name="infocirlce"
                  type="AntDesign"
                />
                {"\nUser-info"}
              </Text>
            </TouchableWithoutFeedback>
          </View>
        </View>
      );
    } else return;
  };

  render() {
    return (
      <React.Fragment>
        {this.UserHomePageDisplay()}
        {this.UserHomePageTabs()}
      </React.Fragment>
    );
  }
}
