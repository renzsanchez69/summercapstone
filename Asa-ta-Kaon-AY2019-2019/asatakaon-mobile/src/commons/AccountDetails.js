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
  CheckBox,
  ScrollView
} from "react-native";
import { Container, Icon, Spinner } from "native-base";
/* -- Custom Components  -- */
import Constants from "./Constants.js";

export default class AccountDetails extends Component {
  state = {
    firstName: "",
    lastName: "",
    email: "",
    address: "",
    gender: ""
  };

  componentDidMount() {
    this.setState({
      email: this.props.doGetLoggedInformation.email
    });
    if (this.props.doGetLoggedInformation.role == Constants.ROLES.USER_ONLY) {
      this.setState({
        firstName: this.props.doGetLoggedInformation.firstName,
        lastName: this.props.doGetLoggedInformation.lastName,
        address: this.props.doGetLoggedInformation.address,
        gender: this.props.doGetLoggedInformation.gender
      });
    }
  }

  validateEmail = email => {
    let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
  };

  validateName = name => {
    return name.length === 0 || name === " " ? true : false;
  };

  submitUpdate = () => {
    if (this.props.doGetLoggedInformation.role == Constants.ROLES.USER_ONLY) {
      const data = {
        firstName: this.state.firstName,
        lastName: this.state.lastName,
        email: this.state.email,
        address: this.state.address
      };
      if (this.validateName(data.firstName)) {
        this.props.doSendAReportMessage("First name must not be blank");
        setTimeout(() => this.props.doSendAReportMessage(""), 1500);
        return;
      } else if (this.validateName(data.lastName)) {
        this.props.doSendAReportMessage("Last name must not be blank");
        setTimeout(() => this.props.doSendAReportMessage(""), 1500);
        return;
      } else if (this.validateName(data.address)) {
        this.props.doSendAReportMessage("Address input must not be blank");
        setTimeout(() => this.props.doSendAReportMessage(""), 1500);
        return;
      } else if (!this.validateEmail(data.email)) {
        this.props.doSendAReportMessage("Email address must be valid");
        setTimeout(() => this.props.doSendAReportMessage(""), 1500);
        return;
      } else this.props.doUpdateUserInfo(data);
    } else if (
      this.props.doGetLoggedInformation.role == Constants.ROLES.RESTAURANT_OWNER
    ) {
      const data = {
        email: this.state.email
      };
      this.props.doUpdateUserInfo(data);
    }
  };

  accountDetailsDisplay = () => {
    if (this.props.doGetLoggedInformation.role == Constants.ROLES.USER_ONLY) {
      return (
        <React.Fragment>
          <View
            style={{
              width: "100%",
              height: 40,
              position: "relative",
              top: 10,
              flexDirection: "row"
            }}
          >
            <Text
              style={{
                height: "100%",
                position: "relative",
                width: "35%",
                textAlignVertical: "center",
                fontSize: 15,
                fontWeight: "bold",
                color: "#000",
                paddingLeft: 10
              }}
            >
              First name
            </Text>
            <TextInput
              value={this.state.firstName}
              style={{
                position: "relative",
                height: "100%",
                width: "50%",
                textAlignVertical: "center",
                borderBottomWidth: 2,
                borderColor: "#000",
                color: "#000"
              }}
              onChangeText={firstName => this.setState({ firstName })}
            />
          </View>
          <View
            style={{
              width: "100%",
              height: 40,
              position: "relative",
              top: 15,
              flexDirection: "row"
            }}
          >
            <Text
              style={{
                height: "100%",
                position: "relative",
                width: "35%",
                textAlignVertical: "center",
                fontSize: 15,
                fontWeight: "bold",
                color: "#000",
                paddingLeft: 10
              }}
            >
              Last name
            </Text>
            <TextInput
              value={this.state.lastName}
              style={{
                position: "relative",
                height: "100%",
                width: "50%",
                textAlignVertical: "center",
                borderBottomWidth: 2,
                borderColor: "#000",
                color: "#000"
              }}
              onChangeText={lastName => this.setState({ lastName })}
            />
          </View>
          <View
            style={{
              width: "100%",
              height: 40,
              position: "relative",
              top: 20,
              flexDirection: "row"
            }}
          >
            <Text
              style={{
                height: "100%",
                position: "relative",
                width: "35%",
                textAlignVertical: "center",
                fontSize: 15,
                fontWeight: "bold",
                color: "#000",
                paddingLeft: 10
              }}
            >
              Address
            </Text>
            <TextInput
              value={this.state.address}
              style={{
                position: "relative",
                height: "100%",
                width: "50%",
                textAlignVertical: "center",
                borderBottomWidth: 2,
                fontSize: 14,
                borderColor: "#000",
                color: "#000"
              }}
              onChangeText={address => this.setState({ address })}
            />
          </View>
          <View
            style={{
              width: "100%",
              height: 40,
              position: "relative",
              top: 25,
              flexDirection: "row"
            }}
          >
            <Text
              style={{
                height: "100%",
                position: "relative",
                width: "35%",
                textAlignVertical: "center",
                fontSize: 15,
                fontWeight: "bold",
                color: "#000",
                paddingLeft: 10
              }}
            >
              E-mail
            </Text>
            <TextInput
              value={this.state.email}
              style={{
                position: "relative",
                height: "100%",
                width: "50%",
                textAlignVertical: "center",
                borderBottomWidth: 2,
                fontSize: 14,
                borderColor: "#000"
              }}
              onChangeText={email => this.setState({ email })}
            />
          </View>

          <View
            style={{
              width: "100%",
              height: 40,
              position: "relative",
              top: 30,
              flexDirection: "row"
            }}
          >
            <Text
              style={{
                height: "100%",
                position: "relative",
                width: "35%",
                textAlignVertical: "center",
                fontSize: 15,
                fontWeight: "bold",
                color: "#000",
                paddingLeft: 10
              }}
            >
              Gender
            </Text>

            <Text
              style={{
                height: "100%",
                position: "relative",
                width: "45%",
                textAlignVertical: "center",
                fontSize: 15,
                fontWeight: "bold",
                color: "#000",
                paddingLeft: 10
              }}
            >
              {this.state.gender}
            </Text>
          </View>
          <TouchableWithoutFeedback onPress={() => this.submitUpdate()}>
            <Text
              style={{
                borderWidth: 1.2,
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
                backgroundColor: "#fff",
                height: 55,
                width: 140,
                position: "relative",
                borderRadius: 20,
                fontWeight: "bold",
                fontSize: 16,
                textAlign: "center",
                textAlignVertical: "center",
                top: 45,
                color: "#000",
                marginBottom: 25
              }}
            >
              Submit
            </Text>
          </TouchableWithoutFeedback>
        </React.Fragment>
      );
    } else if (
      this.props.doGetLoggedInformation.role == Constants.ROLES.RESTAURANT_OWNER
    ) {
      return (
        <React.Fragment>
          <View
            style={{
              width: "100%",
              height: 40,
              position: "relative",
              top: 10,
              flexDirection: "row"
            }}
          >
            <Text
              style={{
                height: "100%",
                position: "relative",
                width: "35%",
                textAlignVertical: "center",
                fontSize: 15,
                fontWeight: "bold",
                color: "#000",
                paddingLeft: 10
              }}
            >
              Address
            </Text>
            <Text
              style={{
                height: "100%",
                position: "relative",
                width: "60%",
                textAlignVertical: "center",
                fontSize: 14,
                color: "#000"
              }}
            >
              Updating here is not applicable to restaurant owners
            </Text>
          </View>
          <View
            style={{
              width: "100%",
              height: 40,
              position: "relative",
              top: 20,
              flexDirection: "row"
            }}
          >
            <Text
              style={{
                height: "100%",
                position: "relative",
                width: "35%",
                textAlignVertical: "center",
                fontSize: 15,
                fontWeight: "bold",
                color: "#000",
                paddingLeft: 10
              }}
            >
              E-mail
            </Text>
            <TextInput
              value={this.state.email}
              style={{
                position: "relative",
                height: "100%",
                width: "50%",
                textAlignVertical: "center",
                borderBottomWidth: 2,
                fontSize: 14,
                borderColor: "#000"
              }}
              onChangeText={email => this.setState({ email })}
            />
          </View>
          <TouchableWithoutFeedback onPress={() => this.submitUpdate()}>
            <Text
              style={{
                borderWidth: 1.2,
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
                backgroundColor: "#fff",
                height: 55,
                width: 140,
                position: "relative",
                borderRadius: 20,
                fontWeight: "bold",
                fontSize: 16,
                textAlign: "center",
                textAlignVertical: "center",
                top: 135,
                marginBottom: 50
              }}
            >
              Submit
            </Text>
          </TouchableWithoutFeedback>
        </React.Fragment>
      );
    }
  };

  render() {
    return (
      <React.Fragment>
        <View
          style={{
            height: "100%",
            width: "100%",
            position: "relative",
            alignItems: "center"
          }}
        >
          <View
            style={{
              height: "8.5%",
              width: "100%",
              position: "relative",
              borderColor: "#ddd",
              borderBottomWidth: 0,
              shadowColor: "#000",
              shadowOffset: {
                width: 0,
                height: 5
              },
              shadowOpacity: 0.34,
              shadowRadius: 6.27,
              elevation: 9,
              backgroundColor: "#555dff",
              flexDirection: "row"
            }}
          >
            <TouchableWithoutFeedback
              onPress={() =>
                this.props.doSetHomePage(Constants.COMMON_ROLE_PAGES.USER_INFO)
              }
            >
              <Text
                style={{
                  height: "50%",
                  width: "18%",
                  position: "relative",
                  color: "#000",
                  fontSize: 13,
                  fontWeight: "bold",
                  textAlign: "center",
                  textAlignVertical: "center",
                  borderRadius: 100,
                  borderWidth: 2,
                  left: "10%",
                  top: "3%"
                }}
              >
                RETURN
              </Text>
            </TouchableWithoutFeedback>

            <Text
              style={{
                height: "100%",
                width: "50%",
                textAlign: "center",
                textAlignVertical: "center",
                fontSize: 15,
                fontWeight: "bold",
                color: "#000",
                left: "50%"
              }}
            >
              Acount Details
            </Text>
          </View>

          <View
            style={{
              borderWidth: 1.2,
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
              backgroundColor: "#fff",
              height: "61%",
              width: "90%",
              borderWidth: 2,
              borderRadius: 20,
              top: "13%",
              paddingTop: "5%"
            }}
          >
            <ScrollView
              style={{ width: "100%" }}
              contentContainerStyle={{
                alignItems: "center",
                paddingBottom: 100
              }}
            >
              {this.accountDetailsDisplay()}
            </ScrollView>
          </View>
        </View>
      </React.Fragment>
    );
  }
}
