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
/* -- Custom Components  -- */
import Constants from "./Constants.js";

export default class SendAReport extends Component {
  state = {
    inputMessage: "",
    inputSubject: ""
  };

  sendReportMessage = () => {
    if (this.state.inputSubject.length == 0) {
      this.props.doSendAReportMessage("Subject message must not be blank");
      setTimeout(() => {
        this.props.doSendAReportMessage("");
      }, Constants.REPORT_DISPLAY_TIME);
    } else if (this.state.inputMessage.length == 0) {
      this.props.doSendAReportMessage("Content must not be left blank");
      setTimeout(() => {
        this.props.doSendAReportMessage("");
      }, Constants.REPORT_DISPLAY_TIME);
    } else {
      this.props.doSendAReportMessage("Sending message, please wait..");
      const messageKey = this.props.doUseFirebaseObject
        .database()
        .ref("ADMIN_MESSAGES")
        .push();
      messageKey
        .update({
          status: "new",
          key: messageKey.key,
          subject: this.state.inputSubject,
          message: this.state.inputMessage,
          sender:
            String(this.props.doGetLoggedInformation.firstName) +
            " " +
            String(this.props.doGetLoggedInformation.lastName),
          senderKey:
            this.props.doGetLoggedInformation.role === Constants.ROLES.USER_ONLY
              ? this.props.doGetLoggedInformation.accountID
              : this.props.doGetLoggedInformation.key,
          email: this.props.doGetLoggedInformation.email
        })
        .then(() => {
          this.props.doSetHomePage(Constants.COMMON_ROLE_PAGES.USER_INFO);
          this.props.doSendAReportMessage("Sent");
          setTimeout(() => {
            this.props.doSendAReportMessage("");
          }, Constants.REPORT_DISPLAY_TIME);
        })
        .catch(error => {
          this.props.doSendAReportMessage("Error in connecting to the server");
          setTimeout(() => {
            this.props.doSendAReportMessage("");
          }, Constants.REPORT_DISPLAY_TIME);
        });
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
                left: "53%"
              }}
            >
              Send Report
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
              height: "60%",
              width: "90%",
              position: "relative",
              elevation: 11,
              top: "15%",
              alignItems: "center",
              paddingTop: "5%",
              borderRadius: 25
            }}
          >
            <Text
              style={{
                height: "9.5%",
                width: "50%",
                textAlign: "center",
                textAlignVertical: "center",
                fontSize: 16,
                fontWeight: "bold",
                color: "#000"
              }}
            >
              Report Subject
            </Text>
            <View
              style={{
                height: "15%",
                width: "70%",
                borderRadius: 100,
                position: "relative",
                borderWidth: 2,
                alignItems: "center"
              }}
            >
              <TextInput
                maxLength={Constants.CREDENTIALS_POLICY.MAX_REPORT_SUBJECT}
                placeholder="Message Subject"
                style={{
                  width: "90%",
                  height: "100%",
                  textAlign: "center",
                  textAlignVertical: "center"
                }}
                onChangeText={inputSubject => this.setState({ inputSubject })}
              />
            </View>

            <Text
              style={{
                height: "9.5%",
                width: "50%",
                textAlign: "center",
                textAlignVertical: "center",
                fontSize: 16,
                fontWeight: "bold",
                color: "#000"
              }}
            >
              Report Content
            </Text>
            <View
              style={{
                height: "40%",
                width: "70%",
                borderRadius: 15,
                position: "relative",
                borderWidth: 2,
                alignItems: "center"
              }}
            >
              <TextInput
                multiline={true}
                maxLength={Constants.CREDENTIALS_POLICY.MAX_REPORT_CONTENT}
                style={{
                  width: "90%",
                  height: "100%",
                  textAlignVertical: "top",
                  paddingLeft: "2%"
                }}
                onChangeText={inputMessage => this.setState({ inputMessage })}
              />
            </View>
            <TouchableWithoutFeedback onPress={() => this.sendReportMessage()}>
              <Text
                style={{
                  height: "15%",
                  width: "45%",
                  position: "relative",
                  textAlignVertical: "center",
                  textAlign: "center",
                  borderRadius: 100,
                  fontWeight: "bold",
                  borderWidth: 2,
                  borderColor: "#000",
                  color: "#000",
                  borderWidth: 1.2,
                  borderColor: "#ddd",
                  borderBottomWidth: 0,
                  shadowColor: "#000",
                  shadowOffset: {
                    width: 0,
                    height: 5
                  },
                  top: "5%",
                  shadowOpacity: 0.34,
                  shadowRadius: 6.27,
                  elevation: 10,
                  backgroundColor: "#fff",
                  fontSize: 15
                }}
              >
                Submit
              </Text>
            </TouchableWithoutFeedback>
          </View>
        </View>
      </React.Fragment>
    );
  }
}
